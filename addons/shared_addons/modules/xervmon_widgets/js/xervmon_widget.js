;
/* global bootbox, SITE_URL, window, setTimeout */
(function ($) {
    "use strict";

    function generateRandomID() {
        return Math.round(Math.random() * 1000000000);
    }

    function promptNewTab() {
        var id = "dashboardTab_" + generateRandomID();
        console.log("promptNewTab", id);
        bootbox.prompt("Name of the new tab:", "Cancel", "Add Tab", function (title) {
            if (title) {
                $.post(SITE_URL + 'admin/xervmon_widgets/addNewTab', {
                    tab_name: title
                }, function (response) {
                    id = "dashboardTab_" + response.message.id;
                    console.log("promptNewTab done", id, arguments);
                    createNewDashboardTab(id, title, true);
                }, 'json');
            }
        }, id);
        return id;
    }

    function confirmDeleteTab(e, tabID, tabTitle) {
        console.log("confirmDeleteTab", arguments);
        tabTitle = tabTitle || "this tab";
        e.preventDefault();
        bootbox.confirm("Are you sure you wan to delete " + tabTitle + "?", function (result) {
            if (result) {
                $.post(SITE_URL + 'admin/xervmon_widgets/deleteTab', {
                    id: tabID.replace("dashboardTab_", "")
                }, function (response) {
                    console.log(response);
                    $("#dashboardTabHolder").bootstrapTabManager("removeTab", tabID);
                }, 'json');
            }
        });
        return false;
    }

    function confirmDeleteWidget(e, widgetID) {
        console.log("confirmDeleteWidget", arguments);

        window.deletedWidgetIDs = window.deletedWidgetIDs || [];
        if ($.inArray(widgetID, window.deletedWidgetIDs) !== -1) {
            return;
        }

        window.confirmDeleteWidgetConfirmOpen = window.confirmDeleteWidgetConfirmOpen || {};
        if (window.confirmDeleteWidgetConfirmOpen[widgetID]) {
            return;
        }

        window.confirmDeleteWidgetConfirmOpen[widgetID] = true;

        /*jshint validthis: true */
        var $this = $(this);
        console.log("confirmDeleteWidget", $this);
        e.preventDefault();

        bootbox.confirm("Are you sure you wan to delete this widget?", function (result) {
            window.confirmDeleteWidgetConfirmOpen[widgetID] = false;
            if (result) {
                $.post(SITE_URL + 'admin/xervmon_widgets/deleteWidget', {
                    id: widgetID.replace("userWidget_", "")
                }, function (response) {
                    console.log(response);
                    window.deletedWidgetIDs.push(widgetID);
                    $this.find("#" + widgetID + " .js-close-button").trigger("click");
                }, 'json');
            }
        });
        return false;
    }

    function loadWidgetsForTab(tabID, $sDashboardArea) {
        // Load widgets for the given tab
        $.get(SITE_URL + 'admin/xervmon_widgets/getWidgetsForTab', {
            tab_id: tabID
        }, function (response) {
            console.log("loadWidgetsForTab", arguments);
            if (response.status && response.status === "success") {
                for (var i = 0, l = response.message.length; i < l; i++) {
                    insertWidget(response.message[i], $sDashboardArea);
                }
            }
        }, 'json');
    }

    function loadUserTabs() {
        console.log("loadUserTabs");
        $.get(SITE_URL + 'admin/xervmon_widgets/getDashboardTabs', {}, function (response) {
            console.log("loadUserTabs Done", response);
            if (response.status && response.status === "success") {
                for (var i = 0, l = response.message.length; i < l; i++) {
                    var tab = response.message[i];
                    var $sDashboardArea = createNewDashboardTab("dashboardTab_" + tab.id, tab.title, true, true);
                    loadWidgetsForTab(tab.id, $sDashboardArea);
                }
                // Activate 1st tab
                // $("#dashboardTabHolder").bootstrapTabManager("activateTab", "dashboardTab_" + response.message[0].id);
            }
        }, 'json');
    }

    function insertWidget(widget, $sDashboardArea) {
        console.log("Inset new widget", arguments);
        $sDashboardArea.sDashboard("addWidget", {
            widgetId: "userWidget_" + widget.id,
            widgetTitle: widget.title,
            widgetContent: widget.content || "",
            widgetType: widget.widget_type || "html",
            disableCloseButton: false,
            enableSettingsButton: true
        }).on("widget-menu.sDashboard", function (e, widgetID) {
            showWidgetSettingsForm(widgetID);
        }).on("widget-close.sDashboard", confirmDeleteWidget);
        var widgetWidthClass = "span6";
        try {
            widget.options = $.parseJSON(widget.options || '{}') || {};
            widget.widget_area = widget.options.widget_width || widget.widget_area;
        } catch (err) {
            widget.options = {};
            console.warn(err.message, err.stack);
        }
        if (widget.widget_area === "full") {
            widgetWidthClass = "span12";
        } else if (widget.widget_area === "three_fourth") {
            widgetWidthClass = "span9";
        } else if (widget.widget_area === "half") {
            widgetWidthClass = "span6";
        } else if (widget.widget_area === "one_fourth") {
            widgetWidthClass = "span3";
        }
        var $thisWidget = $("#userWidget_" + widget.id)
            .addClass(widgetWidthClass);
        $thisWidget.find('.sDashboardWidget').css({
            'color': widget.options.widget_header_text_color,
            'background': widget.options.widget_header_background_color
        });
        $thisWidget.find('.sDashboardWidgetContent').css('background', widget.options.widget_body_background_color);
        try {
            var $thisHeader = $thisWidget.find(".sDashboardWidgetHeader");
            $thisHeader.css({
                'color': widget.options.widget_header_text_color,
                'background': widget.options.widget_header_background_color
            });
            var $sDashboardWidgetHeaderText = $thisHeader.children('.sDashboardWidgetHeaderText');
            if ($sDashboardWidgetHeaderText.length === 0) {
                // Get the text node
                $thisHeader.contents().filter(function () {
                    //Node.TEXT_NODE
                    return this.nodeType === 3;
                }).wrap("<span class='sDashboardWidgetHeaderText' />");
                $sDashboardWidgetHeaderText = $thisHeader.children('.sDashboardWidgetHeaderText');
            }
            if ($sDashboardWidgetHeaderText.length === 0) {
                $sDashboardWidgetHeaderText = $("<span class='sDashboardWidgetHeaderText' />").appendTo($thisHeader);
            }
            $sDashboardWidgetHeaderText.editable({
                type: 'text',
                pk: widget.id,
                url: SITE_URL + 'admin/xervmon_widgets/setWidgetTitle',
                title: 'Edit Widget Title',
                mode: 'inline',
                emptytext: 'Untitled',
                success: function (response, newValue) {
                    console.log("Title changed", response, newValue);
                }
            });
        } catch (err) {
            console.error("x-editable error", err.message, err.stack);
        }
        getWidgetContent(widget.id, widget.refresh_rate || 1000000);
    }

    function showCustomWidgetMenu(e) {
        console.log("showCustomWidgetMenu", arguments, e);
        // Show the menu with option to add new widget
        $('#customWidgetMenu').data("tab-id", $("#dashboardTabHolder").find(".tab-pane.active").attr("id")).modal('show');
        /*$(this).attr("data-tab-id")*/
    }

    function createNewDashboardTab(id, title, closeButton, preventSwitch) {
        console.log("createNewDashboardTab", arguments);
        // Create a new dashboard tab
        var markup = '';
        markup += '<ul class="clearfix dashboard-ul"></ul>';
        // Setup tabs: You can have a default tab here, if you'd like
        var $tabHolder = $("#dashboardTabHolder").bootstrapTabManager("addTab", {
            title: title,
            content: markup,
            id: id,
            enableCloseButton: !! closeButton
        });
        if (!preventSwitch) {
            $tabHolder.bootstrapTabManager("activateTab", id);
        }
        var $sDashboardArea = $("#" + id).children("ul.dashboard-ul").sDashboard({
            dashboardData: []
        }).data("sDashboardArea", $sDashboardArea);
        $sDashboardArea.addClass("row-fluid row-fluid-two-column");
        return $sDashboardArea;
    }

    function generateCustomWidgetMenu(type) {
        console.log("generateCustomWidgetMenu", arguments);
        // Populate the modal based custom widget menu
        var $newWidgetHolder = $("#newWidgetHolder").html("").css("border", 0);
        var $customWidgetMenu_add = $("#customWidgetMenu_add").hide();
        $.get(SITE_URL + 'admin/xervmon_widgets/getWidgetTypes', {
            type: type || ""
        }, function (response) {
            var markup = "",
                widgetGroup = '',
                $thisTabHolder = $('<div class="clearfix" />').appendTo($newWidgetHolder);
            $thisTabHolder.bootstrapTabManager({
                navClass: "nav-tabs",
            });
            if (response.status && response.status === "success") {
                var widgetsByGroup = {};
                for (var i = 0, l = response.message.length; i < l; i++) {
                    var widget = response.message[i];
                    widget = $.isPlainObject(widget) ? widget : {};
                    widgetGroup = widget.widget_module.split('/')[0];
                    widgetsByGroup[widgetGroup] = widgetsByGroup[widgetGroup] || [];
                    var thisMarkup = '';
                    thisMarkup += '<li class="span6 widget-checkbox"><a>';
                    widget.icon_url = widget.icon_url || '../addons/shared_addons/themes/xervmon/img/icons/files.png';
                    thisMarkup += '<label class="checkbox inline" for="createNewWidgetInputCheckbox' + widget.id + '">';
                    thisMarkup += '<input type="checkbox" class="new-widget-checkbox" name="new_widgets" value="' + widget.id + '" id="createNewWidgetInputCheckbox' + widget.id + '" />';
                    thisMarkup += '<img src="' + widget.icon_url + '" class="img-small" />';
                    thisMarkup += '<span class="padding5">' + widget.title + '</span>';
                    thisMarkup += '</label>';
                    thisMarkup += '</a></li>';
                    widgetsByGroup[widgetGroup].push(thisMarkup);
                }
                for (widgetGroup in widgetsByGroup) {
                    if (!widgetsByGroup.hasOwnProperty(widgetGroup)) {
                        continue;
                    }
                    $thisTabHolder.bootstrapTabManager('addTab', {
                        title: widgetGroup,
                        content: '<ul class="row-fluid row-fluid-two-column">' + widgetsByGroup[widgetGroup].join('') + '</ul>',
                        enableCloseButton: false,
                        activate: true
                    });
                }
                setTimeout(function () {
                    // FOrce the checkboxes to show
                    $thisTabHolder.find('input,.checkbox,.radio').trigger('update').trigger('hover');
                }, 0);
                $customWidgetMenu_add.show();
            } else {
                markup += '<div class="alert alert-block span12">No widgets found</div>';
            }
        }, 'json').fail(function () {
            $newWidgetHolder.html('<li class="alert alert-error">An error occured while loading your widgets</li>');
        });
    }

    function getWidgetContent(widgetID, refreshRate) {
        console.log("getWidgetContent", arguments);
        // Load contnent for widget
        var $widgetContent = $("#userWidget_" + widgetID).find(".sDashboardWidgetContent").css({
            "overflow": "false",
            "clear": "both"
        });
        if ($widgetContent.is(":empty")) {
            $widgetContent.html("<i class='icon-spin icon-spinner center text-center'></i>");
        }
        window.geoIPResolverService = "http://freegeoip.net/json/";
        window.storage = window.storage || {};
        window.storage.widgetContentRequests = window.storage.widgetContentRequests || {};
        if (window.storage.widgetContentRequests[widgetID]) {
            return window.storage.widgetContentRequests[widgetID];
        }
        window.storage.widgetContentRequests[widgetID] = $.get(SITE_URL + 'admin/xervmon_widgets/getWidgetContent', {
            id: widgetID
        }, function (response) {
            console.log("Got widget content for", widgetID, response);
            var hasErrors = false,
                approxPositionCode = function (lat, lon) {
                    function approx(num) {
                        return Math.round(num / 10) * 10;
                    }

                    return (approx(lat) + "_" + approx(lon)).replace(/\-/ig, "_");
                }, setupBubbleMapForHostOrService = function (tooltipMarkup, onClick, bubbleLimit) {
                    bubbleLimit = parseInt(bubbleLimit, 10) || 5;
                    $widgetContent.css({
                        "overflow": "visible",
                        "background": "#eee"
                    });
                    var html = '';
                    html += '<div class="text-justified form-inline"><div class="text-justified inline-block control-group" style="margin: 5px;"><label class="inline-block control-label">Status: &nbsp; </label>';
                    html += '<div class="control"><select class="js-service-error-filter">';
                    html += '<option value="any"> Any </option>';
                    html += '<option value="ok"> OK </option>';
                    html += '<option value="error"> Error </option>';
                    html += '</select></div></div>';
                    html += '<div class="text-justified inline-block control-group" style="margin: 5px;"><label class="inline-block control-label">Region: &nbsp; </label>';
                    html += '<div class="control"><select class="js-service-region-filter">';
                    html += '<option value="world"> Worldwide </option>';
                    html += '<option value="usa"> USA </option>';
                    html += '</select></div></div></div>';
                    html += '<hr/>';
                    html += '<div class="alert alert-warning js-show-all-bubble-details hide cursor-pointer"> Some hosts/processes are hidden. Click here to view all of them. </div>';
                    html += '<div class="js-service-map" style="min-height:380px;"></div>';
                    $widgetContent.html(html);

                    $widgetContent.on("click", ".close", function (e) {
                        // Handle alert close action so that show-all isn't triggered by pyrocms
                        e.preventDefault();
                        $(this).closest(".alert").fadeOut();
                        return false;
                    });
                    $widgetContent.off("click.bubbleShowAll").on("click.bubbleShowAll", ".js-show-all-bubble-details", function () {
                        if ($.isFunction(onClick)) {
                            onClick.apply(this, [{}]);
                            //console.log("Bubble showall", this, arguments);
                            //console.trace();
                        }
                    });

                    var clickSetupTimeout = false;

                    function setupCircleClickEvent() {
                        if (clickSetupTimeout !== false) {
                            return;
                        }
                        clickSetupTimeout = setTimeout(function () {
                            // stackoverflow.com/questions/14431361/event-delegation-on-svg-elements
                            $widgetContent.find(".datamaps-bubble").off("click").on("click", function () {
                                var info = $(this).data("info");
                                console.log("Circle clicked", info);
                                if ($.isFunction(onClick)) {
                                    onClick.apply(this, [info]);
                                    // console.log("Bubble onclick", this, arguments, info);
                                }
                            });
                            clickSetupTimeout = false;
                        }, 500);
                    }

                    function prepareData() {
                        var bubblesHidden = data.length > bubbleLimit;
                        data = data.slice(0, bubbleLimit);
                        console.log("Sliced data", data);
                        var posCount = {};
                        $.each(data, function (index, item) {
                            var pos = approxPositionCode(item.latitude, item.longitude),
                                thisPosCount = parseInt(posCount[pos], 10) || 0;
                            data[index].radius = 9 * ++thisPosCount;
                            posCount[pos] = thisPosCount;
                        });
                        data.sort(function (a, b) {
                            return b.radius - a.radius;
                        });
                        console.log("Prepared data", data);
                        if (bubblesHidden) {
                            $widgetContent.find(".js-show-all-bubble-details").fadeIn();
                        } else {
                            $widgetContent.find(".js-show-all-bubble-details").fadeOut();
                        }
                    }

                    var defaults = {
                        fills: {
                            'green': 'rgba(44, 160, 44, 0.78)',
                            'red': 'rgba(237, 28, 36, 0.78)',
                            defaultFill: 'rgba(38, 122, 248, 0.8)'
                        },
                        scope: 'world'
                    };
                    setupBubbleMap($widgetContent.children('.js-service-map').html(""), response.message.content, defaults, tooltipMarkup);
                    setupCircleClickEvent();
                    var data = response.message.content;
                    var $statusFilter = $widgetContent.find('.js-service-error-filter').on("change", function () {
                        var val = $(this).val();
                        window.storage.serviceMapSelectedErrorFilter = val;
                        switch (val) {
                        case 'error':
                            data = $.grep(response.message.content, function (item) {
                                return item.State.toLowerCase().indexOf("error") > -1;
                            });
                            break;
                        case 'ok':
                            data = $.grep(response.message.content, function (item) {
                                return item.State.toLowerCase().indexOf("ok") > -1;
                            });
                            break;
                        default:
                            data = response.message.content;
                            break;
                        }
                        prepareData();
                        setupBubbleMap($widgetContent.children('.js-service-map').html(""), data, defaults, tooltipMarkup);
                        setupCircleClickEvent();
                    });
                    var $regionFilter = $widgetContent.find('.js-service-region-filter').on("change", function () {
                        var val = $(this).val();
                        window.storage.serviceMapSelectedRegionFilter = val;
                        defaults.scope = val;
                        prepareData();
                        setupBubbleMap($widgetContent.children('.js-service-map').html(""), data, defaults, tooltipMarkup);
                        setupCircleClickEvent();
                    });

                    if (hasErrors) {
                        $statusFilter.val("error").trigger("change");
                    }

                    if (window.storage.serviceMapSelectedRegionFilter) {
                        $regionFilter.val(window.storage.serviceMapSelectedRegionFilter).trigger("change");
                    } else {
                        $regionFilter.val('usa').trigger("change");
                    }
                };
            if (response.status && response.status === "success") {
                switch (response.message.widgetDefinition.widget_type) {
                case 'table':
                    var $table = $(buildTableFromArray($.makeArray(response.message.content), ["created_on"]));
                    $table.appendTo($widgetContent.html(""));
                    // $table.dataTable({
                    // bJQueryUI : true
                    // }).addClass("TableContainer");
                    setupTableSorter($table, false);
                    //$table.setupToggleColumnsContextMenu();
                    break;
                case 'chart':
                    $widgetContent.html('<svg class="js-widget-chart"></svg>');
                    response.message.content = response.message.content || {};
                    generatePieChart(response.message.content.data, {
                        container: "#userWidget_" + widgetID + ' svg.js-widget-chart',
                        titleKey: response.message.content.titleKey,
                        countKey: response.message.content.countKey
                    });
                    break;
                case 'barchart':
                    $widgetContent.html('<svg class="js-widget-chart"></svg>');
                    generateBarChart(response.message.content, {
                        container: "#userWidget_" + widgetID + ' svg.js-widget-chart'
                    });
                    break;
                case 'count_boxes':
                    response.message.content = response.message.content || {};
                    $widgetContent.generateCountBoxes(response.message.content.data, {
                        titleKey: response.message.content.titleKey,
                        descriptionKey: response.message.content.descriptionKey,
                        countKey: response.message.content.countKey,
                        colorKey: response.message.content.colorKey,
                        linkKey: response.message.content.linkKey,
                        iconKey: response.message.content.iconKey
                    });
                    break;
                case 'map_bubble':
                    $widgetContent.html('');
                    setupBubbleMap($widgetContent, response.message.content.data, response.message.content.options, function (geo, data) {
                        return data.name;
                    });
                    break;
                case 'map_bubble_hosts':
                    // Get lat/long from IPs
                    var posCount = {};
                    var ipResolveCalls = $.map(response.message.content, function (item, index) {
                        return $.get(window.geoIPResolverService + item.Address, {}, function (location) {
                            // response.message.content[index].location = location;
                            var pos = approxPositionCode(location.latitude, location.longitude),
                                thisPosCount = parseInt(posCount[pos], 10) || 0;
                            response.message.content[index].latitude = location.latitude;
                            response.message.content[index].longitude = location.longitude;
                            response.message.content[index].radius = 9 * ++thisPosCount;
                            response.message.content[index].country = location.country_code;
                            var isError = response.message.content[index].State.toLowerCase().indexOf("error") > -1;
                            hasErrors = hasErrors || isError;
                            response.message.content[index].fillKey = isError ? 'red' : 'green';
                            posCount[pos] = thisPosCount;
                        }, "json");
                    });
                    $.when.apply($, ipResolveCalls).always(function () {
                        response.message.content.sort(function (a, b) {
                            return b.radius - a.radius;
                        });
                        console.log("With lat/long", response.message.content);
                        var setupMap = function () {
                            setupBubbleMapForHostOrService(function (geo, data) {
                                var html = '<div class="img-polaroid inline-block">';
                                html += '<b>' + data.Name + "</b> (" + data.Address + ") ";
                                html += '<br/> ' + data.State;
                                html += '</div>';
                                return html;
                            }, function (info) {
                                // Circle-click action
                                window.location = SITE_URL + 'admin/SystemMonitor/hostsByGroup/index?host=' + info.Name;
                            });
                        };
                        ////setTimeout(setupMap, 200);
                        setupMap();
                        var tabID = $widgetContent.closest(".tab-pane").attr("id");
                        $widgetContent.closest(".tab-content").prev("ul").find('li>a[href="#' + tabID + '"]').off("shown.userWidget_" + widgetID).on("shown.userWidget_" + widgetID, setupMap);
                    });
                    break;
                case 'map_bubble_services':
                    // Get lat/long from IPs
                    var posCount = {}, ipResolveCalls = $.map(response.message.content, function (item, index) {
                            return $.get(window.geoIPResolverService + item.Address, {}, function (location) {
                                // response.message.content[index].location = location;
                                var pos = approxPositionCode(location.latitude, location.longitude),
                                    thisPosCount = parseInt(posCount[pos], 10) || 0;
                                response.message.content[index].latitude = location.latitude;
                                response.message.content[index].longitude = location.longitude;
                                response.message.content[index].radius = 3 * ++thisPosCount;
                                response.message.content[index].country = location.country_code;
                                var isError = response.message.content[index].State.toLowerCase().indexOf("error") > -1;
                                hasErrors = hasErrors || isError;
                                response.message.content[index].fillKey = isError ? 'red' : 'green';
                                posCount[pos] = thisPosCount;
                            }, "json");
                        });
                    $.when.apply($, ipResolveCalls).always(function () {
                        response.message.content.sort(function (a, b) {
                            return b.radius - a.radius;
                        });
                        console.log("With lat/long", response.message.content);
                        var setupMap = function () {
                            setupBubbleMapForHostOrService(function (geo, data) {
                                var html = '<div class="img-polaroid inline-block">';
                                html += '<b>' + data["Service Name"] + "</b>, <br/>";
                                html += '<b>' + data.Name + "</b> (" + data.Address + ") ";
                                html += '<br/> ' + data.State;
                                html += '</div>';
                                return html;
                            }, function (info) {
                                // Circle-click action
                                window.location = SITE_URL + 'admin/SystemMonitor/services/index?host=' + info.Name + '&serviceName=' + info['Service Name'];
                            });
                        };
                        //setTimeout(setupMap, 200);
                        setupMap();
                        var tabID = $widgetContent.closest(".tab-pane").attr("id");
                        $widgetContent.closest(".tab-content").prev("ul").find('li>a[href="#' + tabID + '"]').off("shown.userWidget_" + widgetID).on("shown.userWidget_" + widgetID, setupMap);
                    });
                    break;
                default:
                case 'html':
                    $widgetContent.html(response.message.content);
                    break;
                }
            } else {
                $widgetContent.text("Failed to get data for this widget (ID:" + widgetID + ")");
            }
        }, 'json').fail(function () {
            $widgetContent.text("An error occured while trying to get data for this widget (ID:" + widgetID + ")");
        }).always(function () {
            window.storage = window.storage || {};
            window.storage.widgetContentRequests[widgetID] = false;
            refreshRate = Math.max(parseInt(refreshRate, 10) || 60000, 30000);
            window.storage.widgetContentTimeouts = window.storage.widgetContentTimeouts || {};
            if (!window.storage.widgetContentTimeouts[widgetID]) {
                window.storage.widgetContentTimeouts[widgetID] = setTimeout(function () {
                    try {
                        clearTimeout(window.storage.widgetContentTimeouts[widgetID]);
                        window.storage.widgetContentTimeouts[widgetID] = false;
                    } catch (err) {
                        console.warn(err.message, err.stack);
                    }
                    getWidgetContent(widgetID, refreshRate);
                }, refreshRate);
            }
        });
        return window.storage.widgetContentRequests[widgetID];
    }

    function createNewWidget(tabID, widgetTypeID) {
        console.log("createNewWidget", arguments);
        // Create a new widget
        $.post(SITE_URL + 'admin/xervmon_widgets/addNewWidget', {
            tab_id: tabID.replace('dashboardTab_', ''),
            widget_type_id: widgetTypeID
        }, function (response) {
            if (response.status && response.status === "success") {
                var widget = response.message;
                insertWidget(widget, $("#" + tabID).find('ul.sDashboard'));
            }
        }, 'json');
    }

    function showWidgetSettingsForm(widgetID) {
        // Show the form for the given widget

        window.showWidgetSettingsFormOpen = window.showWidgetSettingsFormOpen || {};
        if (window.showWidgetSettingsFormOpen[widgetID]) {
            return;
        }

        var id = widgetID.replace("userWidget_", "");

        window.showWidgetSettingsFormOpen[widgetID] = true;
        $.get(SITE_URL + 'admin/xervmon_widgets/getWidgetFormData', {
            id: id
        }, function (response) {
            console.log("showNewWidgetForm", widgetID, response, response.status === "success");
            if (response.status === "success") {
                var widget = response.message.widget;
                var widgetDefinition = response.message.definition;
                var fields = [{
                    "type": "select",
                    "label": "Width",
                    "attributes": {
                        "name": "widget_width"
                    },
                    "fields": [{
                        label: "Full",
                        "attributes": {
                            "value": "full"
                        }
                    }, {
                        label: "Three-Fourth",
                        "attributes": {
                            "value": "three_fourth"
                        }
                    }, {
                        label: "Half",
                        "attributes": {
                            "value": "half"
                        }
                    }, {
                        label: "One-Fourth",
                        "attributes": {
                            "value": "one_fourth"
                        }
                    }]
                }, {
                    "type": "text",
                    "label": "Box Background Color",
                    "attributes": {
                        "name": "widget_header_background_color",
                        "class": 'input-small js-colorpicker'
                    }
                }, {
                    "type": "text",
                    "label": "Box Text Color",
                    "attributes": {
                        "name": "widget_header_text_color",
                        "class": 'input-small js-colorpicker'
                    }
                }, {
                    "type": "text",
                    "label": "Body Background Color",
                    "attributes": {
                        "name": "widget_body_background_color",
                        "class": 'input-small js-colorpicker'
                    }
                }];
                fields.push.apply(fields, $.parseJSON(widgetDefinition.options || "[]"));
                fields.push({
                    "type": "buttons",
                    "fields": [{
                        "attributes": {
                            "value": "Save",
                            "type": "submit",
                            "class": "btn btn-success xervmon-btn-big"
                        }
                    }, {
                        "attributes": {
                            "value": "Cancel",
                            "type": "reset",
                            "class": "btn cancelButton gray xervmon-btn-big cancel",
                            "data-dismiss": "modal"
                        }
                    }]
                });
                var markup = '';
                markup += '<div class="modal hide fade">';
                markup += '<div class="modal-header">';
                markup += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                markup += '<h4>Settings for ' + widget.title + ':</h4>';
                markup += '</div>';
                markup += '<div class="modal-body text-center">';
                markup += window.generateFormFromJSON(fields);
                markup += '</div>';
                // markup += '<div class="modal-footer">';
                // markup += '<a class="btn" data-dismiss="modal" aria-hidden="true">Cancel</a>';
                // markup += '</div>';
                markup += '</div>';
                var $modal = $(markup).appendTo('body'),
                    $form = $modal.find("form");
                $form.setFormValues(widget.options);
                $modal.modal('show');
                $form.on("submit", function (e) {
                    e.preventDefault();
                    var options = $(this).serializeObject();
                    console.log("Submitted form", this, options);
                    $.post(SITE_URL + 'admin/xervmon_widgets/setWidgetOptions', {
                        id: id,
                        value: options
                    }, function (response) {
                        console.log("Saved form", response);
                    }, "json");
                    $modal.modal('hide');

                    var widgetWidthClass;
                    if (options.widget_width === "full") {
                        widgetWidthClass = "span12";
                    } else if (options.widget_width === "three_fourth") {
                        widgetWidthClass = "span9";
                    } else if (options.widget_width === "half") {
                        widgetWidthClass = "span6";
                    } else if (options.widget_width === "one_fourth") {
                        widgetWidthClass = "span3";
                    }
                    var $widget = $("#" + widgetID)
                        .removeClass('span12')
                        .removeClass('span9')
                        .removeClass('span6')
                        .removeClass('span3')
                        .addClass(widgetWidthClass);
                    $widget.find('.sDashboardWidget').css({
                        'color': options.widget_header_text_color,
                        'background': options.widget_header_background_color
                    });
                    $widget.find('.sDashboardWidgetContent').css('background', options.widget_body_background_color);
                    $widget.find('.sDashboardWidgetHeader').css({
                        'color': options.widget_header_text_color,
                        'background': options.widget_header_background_color
                    });
                    console.log("Widget", $widget, widgetWidthClass);
                    return false;
                });
            } else {
                // Should never happen
                bootbox.alert("Sorry, the widget you selected no longer exists", function () {
                    //window.location.reload();
                });
            }
        }, 'json').complete(function () {
            window.showWidgetSettingsFormOpen[widgetID] = false;
        });
    }

    function setupDashboardWidgets() {
        console.log("setupDashboardWidgets", arguments);
        // Load the add widget menu
        generateCustomWidgetMenu();
        // Setup the initial dashboard widgets : 1st tab
        var $dashboardTabHolder = $("#dashboardTabHolder").bootstrapTabManager({
            addTabButtonEnabled: true,
            navClass: "nav-tabs xervmon-contentTab"
        });
        $dashboardTabHolder.on("newTab.bootstrapTabManager", promptNewTab);
        $dashboardTabHolder.on("click", ".add-new-widget", showCustomWidgetMenu);
        $dashboardTabHolder.on("closeTab.bootstrapTabManager", confirmDeleteTab);
        $dashboardTabHolder.append('<a class="btn pull-right addNewWidgetBtn add-new-widget"><span class="bulbIcon"></span>ADD NEW WIDGET</a>');
        $("#customWidgetMenu_add").on("click", function () {
            var $customWidgetMenu = $("#customWidgetMenu");
            var $selectedWidgets = $customWidgetMenu.find(".new-widget-checkbox:checked");
            $selectedWidgets.each(function () {
                var $this = $(this);
                createNewWidget($('#customWidgetMenu').data("tab-id"), $this.val());
                $this.prop("checked", false);
            });
            $customWidgetMenu.modal('hide');
        });
        //$("#sortable").hide();
        var $sDashboardArea = createNewDashboardTab("dashboardTabHolder_primary", "Default", false, false, true);
        // Migrate older widgets : re-enabled on request
        $("#sortable").hide().find(".accordion-group").each(function (index) {
            var $this = $(this);
            $sDashboardArea.sDashboard("addWidget", {
                widgetId: "oldWidget_" + index,
                widgetTitle: $this.find(".accordion-heading").text(),
                widgetContent: $this.find(".accordion-body").html(),
                disableCloseButton: true,
                enableSettingsButton: false
            });
            $("#oldWidget_" + index).addClass("span12");
        });

        // Hide the add new widget button on the primary tab
        $dashboardTabHolder.data("bootstrapTabManager").$tabNav.on("show", 'a:not([href="#dashboardTabHolder_primary"])', function () {
            $('.addNewWidgetBtn').show();
            $(window).trigger('resize');
        }).find('a[href="#dashboardTabHolder_primary"]').on("show", function () {
            $('.addNewWidgetBtn').hide();
            $(window).trigger('resize');
        });
        $('.addNewWidgetBtn').hide();


        loadUserTabs();
    }

    function setupDashboardTour() {
        // Setup dashboard tour
        // Instance the tour
        var tour = new Tour({
            onShow: function (t) {
                // Open the accordion if it is one
                console.log('tour onShow:', this, arguments);
                var $this = $(this.element);
                if ($this.hasClass("collapse")) {
                    $this.collapse("show");
                }
            },
            onHide: function (t) {
                // Close the accordion if it is one
                console.log('tour onHide:', this, arguments);
                var $this = $(this.element);
                if ($this.hasClass("collapse")) {
                    $this.collapse("hide");
                }
            }
        });

        // Add your steps. Not too many, you don'
        tour.addSteps([{
                element: 'li.helper-tab[title="Add New Tab"]', // string (jQuery selector) - html element next to which the step popover should be shown
                title: "Add new tab", // string - title of the popover
                content: "Add a new dashboard tab" // string - content of the popover
            }, {
                element: ".addNewWidgetBtn", // string (jQuery selector) - html element next to which the step popover should be shown
                title: "Add new widget", // string - title of the popover
                content: "Add a new widget on this tab" // string - content of the popover
            }, {
                element: "#sidebarAccordionCollapse0", // string (jQuery selector) - html element next to which the step popover should be shown
                title: "Manage Cloud", // string - title of the popover
                content: "Manage Cloud Assets & Requisitions" // string - content of the popover
            }, {
                element: "#sidebarAccordionCollapse1",
                title: "Monitor Cloud",
                content: "Monitor & Control Cloud Assets"
            },
            {
                element: "#sidebarAccordionCollapse3",
                title: "Marketplace",
                content: "Subscribe, Install & use Xervmon Apps"
            },
            {
                element: "#sidebarAccordionCollapse2",
                title: "Admin Settings",
                content: "Role Based Access & Business Metadata"
            },
            {
                element: "#sidebarAccordionCollapse4",
                title: "Profile Settings",
                content: "Edit your Profile"
            },
            {
                element: "#sidebarAccordionCollapse5",
                title: "Setup Service Provider",
                content: "Manage your Service Provider"
            },
            {
                element: "#sidebarAccordionCollapse6",
                title: "Billing & Reports",
                content: "Manage your reports"
            }
        ]);

        // Initialize the tour
        tour.init();

        // Start the tour
        tour.start();

        return tour;

    }

    // Globalize methods
    window.setupDashboardWidgets = setupDashboardWidgets;
    window.setupDashboardTour = setupDashboardTour;

    $.fn.editable.defaults.mode = 'inline';

    jQuery(function ($) {
        "use strict";

        try {
            window.setupDashboardWidgets();
        } catch (err) {
            console.error(err.message, err.stack);
        }

        try {
            window.setupDashboardTour();
        } catch (err) {
            console.error(err.message, err.stack);
        }

        $(".toggle-accordion").on("click", function (e) {
            // Toggle the dashboard blocks
            e.preventDefault();
            var $this = $(this),
                $body = $this.closest(".accordion-group").children().last();
            console.log("Toggling dashbosrd block", this, $body.get(0));
            if ($body.is(":visible")) {
                $body.fadeOut();
                $this.html('<i class="icon-chevron-down"></i>');
            } else {
                $body.fadeIn();
                $this.html('<i class="icon-chevron-up"></i>');
            }
            return false;
        });

        $('#remove_installer_directory').on('click', function (e) {
            e.preventDefault();
            var $parent = $(this).parent();
            $.get(SITE_URL + 'admin/remove_installer_directory', function (data) {
                $parent.removeClass('warning').addClass(data.status).html(data.message);
            });
        });
    });

})(jQuery);
