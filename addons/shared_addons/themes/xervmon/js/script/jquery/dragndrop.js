;(function($) {"use strict";

    var endPointOptsCommon, allowableTargets, $serverDetailsForm, baseURL, regionToCurrencyMapping, formDataMapping, budget, serverIconMap, serverBackgroundMap, connectionOptions, connectionCustomizations, connectionLimitMapping, locallyGlobalStorage = {}, deploymentStatusTimeout;

	baseURL ="http://devmaas.xervmon.com/sudhi_test/operation-kriya";
    //baseURL = SITE_URL;
    //"http://maas.xervmon.com/develop/operation-kriya/index.php";

    // Default options for endpoints
    endPointOptsCommon = {
        endpoint : "Dot",
        paintStyle : {
            width : 18,
            height : 18,
            fillStyle : '#CF8500'
        },
		
        isSource : true,
        isTarget : true,
        connectorStyle : {
            strokeStyle : "#ddd",
            lineWidth : 4,
            joinstyle : "round",
            outlineColor : "#EAEDEF",
            outlineWidth : 2
        },
        connectorOverlays : [["Arrow", {
            location : 0.5
        }]],
        connector : ["Flowchart", {
            stub : [40, 60],
            gap : 10,
            cornerRadius : 5
        }]
    };

    // Default connection options : keep in-sync with endPointOptsCommon
    connectionOptions = {
        endpoint : "Rectangle",
        endpointStyle : {
            width : 20,
            height : 20,
            fillStyle : '#ddd'
        },
        connector : ["Flowchart", {
            stub : [40, 60],
            gap : 10,
            cornerRadius : 5
        }],
        paintStyle : {
            strokeStyle : "#ddd",
            lineWidth : 4,
            joinstyle : "round",
            outlineColor : "#EAEDEF",
            outlineWidth : 2
        },
        overlays : [["Arrow", {
            location : 0.5
        }]]
    };

    // Max-Connections by type
    connectionLimitMapping = {
        "CloudServer" : -1,
        "Load Balancer" : -1,
        "HA-Load Balancer" : -1,
        "CloudDatabase" : -1,
        "Compute" : -1,
        "Block Storage" : 1,
        "Volume" : 1,
        "Cinder" : 1
    };

    // Describes which types of servers can be connected
    // Source to possible destinations mapping
    allowableTargets = {
        "CloudServer" : ["Load Balancer", "HA-Load Balancer", "CloudDatabase"],
        "Load Balancer" : [],
        "HA-Load Balancer" : [],
        "CloudDatabase" : [],
        "Volume" : ["CloudServer"],
        "Compute" : ["Load Balancer", "HA-Load Balancer", "CloudDatabase"],
        "Block Storage" : ["CloudServer"],
        "Cinder" : ["Compute"]
    };

    regionToCurrencyMapping = {
        "US" : "USD",
        "UK" : "GBP"
    };

    formDataMapping = {
        "Rackspace Cloud" : {
            "CloudServer" : ["server_name", "no_instances", "region_geo", "pre_built_stack", "server_cost", "monthly_cost"],
            "Volume" : ["vol_name", "region_geo", "server_cost", "monthly_cost", "vol_size"],
            "Load Balancer" : ["lb_name", "virtual_ip", "protocol", "port", "virtual_ip_2", "algorithm", "region_geo", "server_cost", "monthly_cost"],
            "HA-Load Balancer" : ["lb_name", "virtual_ip", "protocol", "port", "virtual_ip_2", "algorithm", "region_geo", "server_cost", "monthly_cost"],
            "CloudDatabase" : ["server_name", "no_instances", "region_geo", "server_cost", "monthly_cost", "disk_size", "db_name", "username", "password"]
        },
        "HP Cloud" : {
            "CloudServer" : ["server_name", "no_instances", "region_geo", "pre_built_stack", "server_cost", "monthly_cost"],
            "Block Storage" : ["vol_name", "region_geo", "server_cost", "monthly_cost", "vol_size"],
            "HA-Load Balancer" : ["lb_name", "virtual_ip", "protocol", "port", "virtual_ip_2", "algorithm", "region_geo", "server_cost", "monthly_cost"]
        },
        "OpenStack" : {
            "Compute" : ["server_name", "no_instances", "region_geo", "pre_built_stack", "server_cost", "monthly_cost"],
            "Cinder" : ["vol_name", "region_geo", "server_cost", "monthly_cost", "vol_size"],
            "HA-Load Balancer" : ["lb_name", "virtual_ip", "protocol", "port", "virtual_ip_2", "algorithm", "region_geo", "server_cost", "monthly_cost"]
        }
    };

    serverIconMap = {
        "CloudServer" :"img/cloudServer.png",
        "Volume" : "img/volume.png",
        "CloudDatabase" :"img/cloudDatabase.png",
        "Compute" :"",
        "Block Storage" :"",
        "Cinder" :"",
        "fallback" : "",
		"HA-Load Balancer" : "img/HAloadbalancer.png"
    };

    serverBackgroundMap = {
        "CloudServer" : "bg.png",
        "Volume" : "bg.png",
        "CloudDatabase" :"bg.png",
        "Load Balancer" : "bg.png",
        "HA-Load Balancer" : "bg.png",
        "Compute" :"bg.png",
        "Block Storage" :"bg.png",
        "Cinder" :"bg.png"
    };

    // Must be in sync with allowableTargets
    // Change the color, etc of specific connections
    connectionCustomizations = {
        "CloudServer:CloudDatabase" : {
            paintStyle : {
                strokeStyle : "#ed1c24" // Red
            }
        },
        "CloudServer:Load Balancer" : {
            paintStyle : {
                strokeStyle : "#1CA724" // Green
            }
        },
        "CloudServer:HA-Load Balancer" : {
            paintStyle : {
                strokeStyle : "#1CA724" // Green
            }
        },
        "Volume:CloudServer" : {
            paintStyle : {
                strokeStyle : "#EDCC1C" // Orange
            }
        },
        "Block Storage:CloudServer" : {
            paintStyle : {
                strokeStyle : "#EDCC1C" // Orange
            }
        },
        "Cinder:Compute" : {
            paintStyle : {
                strokeStyle : "#EDCC1C" // Orange
            }
        },
        "Compute:HA-Load Balancer" : {
            paintStyle : {
                strokeStyle : "#1CA724" // Green
            }
        }
    };

    function isString(str) {
        // Check if object is a string
        return !!( typeof str == 'string' || str instanceof String);
    }

    function preventDefault(e) {
        // Trying all the various prevent-Default mechanisms
        // To ensure that the event's default action is prevented and it's propagation stopped
        if (e.preventDefault) {
            e.preventDefault();
        }
        if (e.stopImmediatePropagation) {
            e.stopImmediatePropagation();
        }
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        if (e.stop) {
            e.stop();
        }
        e.returnValue = false;
        return false;
    }

    function scrollToElement(element) {
        //Smooth scroll to slected element
        try {
            var $this = $(element);
            $('html, body').animate({
                scrollTop : Math.max($this.offset().top - 20, 0)
            }, 500);
        } catch(err) {
            console.error("scrollToElement: An error occured", err.message, err.stack);
        }
    }

    function updateWrapperHeight() {
        // Change wrapper height to match the row-fluid
        var $wrapper = $("#jsPlumbSuperWrapper");
        $wrapper.height($wrapper.closest(".row-fluid").height() - $("#serviceAccordian").outerHeight());
    }

    function getImages(details, form) {
        $.post(baseURL + "/admin/dplanner/getImages", {
            cloudProvider : details.cloudProvider,
            serviceTypeId : details.typeID
        }, function(dropdownMarkup) {
            $(form).find(".images-dropdown-holder").html($.parseJSON(dropdownMarkup));
        });
    }

    function getConnectionOptions(type) {
        var options = "";
        $(".js-server").each(function() {
            var $server = $(this), serverType = $server.attr("data-type");
            if (((allowableTargets[serverType] || []).indexOf(type) !== -1) || ((allowableTargets[type] || []).indexOf(serverType) !== -1)) {
                // If they can connect, add as option
                options += '<option value="' + $server.attr("data-id") + '">' + $server.attr("data-name") + '</option>'
            }
        });
        return options;
    }

    function getFlavorDetails(id) {
        // Get flavor info given the ID
        var serviceFlavors = locallyGlobalStorage["serviceFlavors"] || [];
        for (var i = 0, j = serviceFlavors.length; i < j; i++) {
            var flavor = serviceFlavors[i];
            if (flavor.flavorId === id) {
                return flavor;
            }
        }
        return {};
    }

    function getFlavorOptions(type, id, flavorID) {
        var options = "", thisFlavors, serviceFlavors = locallyGlobalStorage["serviceFlavors"] || [];

        // Get flavors that belong to this service type
        thisFlavors = $.grep(serviceFlavors, function(flavor, index) {
            return flavor.service_type_id === id;
        });

        if (thisFlavors.length === 0) {
            // Create a dummy instance if there are none
            thisFlavors = [{
                flavorId : "Temp",
                name : type + " Sample Instance"
            }];
        }

        for (var m = 0, n = thisFlavors.length; m < n; m++) {
            var thisFlavor = thisFlavors[m];
            // Use 1st flavor if not set
            flavorID = $.isNumeric(flavorID) ? flavorID : thisFlavor.flavorId;
            options += '<option value="' + thisFlavor.flavorId + '" ' + (flavorID === thisFlavor.flavorId ? 'selected="selected"' : '') + '>' + thisFlavor.name + '</option>';
        }

        return options;
    }

    function createServerPlum(plumbInstance, container, details) {
        // Create a new server plum with the given instance

        var serverMarkup, $server;
        serverMarkup = '<div id="serverItem_' + details.id + '" data-name="' + JSON.stringify(details.type) + '" data-type="' + details.type + '" data-id="' + details.id + '" class="js-server box-shiny inline-block ' + (serverBackgroundMap[details.type] ? '' : 'box-shiny-background') + '" style="background-image:url(' + serverBackgroundMap[details.type] + ')">';
        serverMarkup += '<button type="button" title="Delete" class="server-close close js-close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        serverMarkup += '<div class="js-box-shiny-content box-shiny-content"><br/>';
        if (serverIconMap[details.type]) {
            serverMarkup += '<img src="' + serverIconMap[details.type] + '" class="box-shiny-icon" /><br/>';
        }
        serverMarkup += '<div class="serverbox-name"></div> ';
        // serverMarkup += '<div class="serverbox-flavor-name"></div> ';
        // serverMarkup += '<div>' + details.type + '</div> ';
        serverMarkup += '<div><b class="serverbox-count"></b></div> ';
        serverMarkup += '</div></div>';
        $server = $(serverMarkup).appendTo(container);

        if (details.position) {
            // Place it where dropped/specified
            $server.css("position", "absolute").css(details.position);
        }

        console.log("Creating server plum", this, arguments);

        var thisEndpointOpts = $.extend({}, endPointOptsCommon, {
            maxConnections : connectionLimitMapping[details.type] || -1
        });

        // Create an endpoint for connectors & save it in the data attr
        $server.data({
            "endpoint-top" : plumbInstance.addEndpoint($server, $.extend({}, thisEndpointOpts, {
                anchor : "TopCenter"
            })),
            "endpoint-right" : plumbInstance.addEndpoint($server, $.extend({}, thisEndpointOpts, {
                anchor : "RightMiddle"
            })),
            "endpoint-bottom" : plumbInstance.addEndpoint($server, $.extend({}, thisEndpointOpts, {
                anchor : "BottomCenter"
            })),
            "endpoint-left" : plumbInstance.addEndpoint($server, $.extend({}, thisEndpointOpts, {
                anchor : "LeftMiddle"
            }))
        });

        // Setup handler for editing properties on dblclick
        $server.on("dblclick", editServerProperties);

        $server.on("click", ".js-close", function() {
            // Apply a close action
            return removeServerPlum(plumbInstance, $server);
        });

        // Make it draggable
        $server.draggable({
            containment : "parent",
            handle : ".js-box-shiny-content",
            drag : function() {
                setTimeout(function() {
                    try {
                        plumbInstance.repaintEverything();
                    } catch(err) {
                        console.warn("Repaint failed", err.message, err.stack)
                    }
                }, 100);
            },
            scroll : true
        });

        console.log("New server plum", details);

        // Add it's form to serverDetailsForm
        $.post(baseURL + "/admin/dplanner/generateForm", {
            cloudProvider : details.cloudProvider,
            serviceTypeId : details.typeID
        }, function(formMarkup) {
            // Add the generated form to the form holder on the right

            window.plumCounter = (parseInt(window.plumCounter, 10) || 0) + 1;

            var markup = '', domID = "form_" + details.id, $formMarkup = $(formMarkup);
            $formMarkup.find("form").replaceWith("<div class='form' />")
            markup += '<div id="' + domID + '_wrapper" data-id="' + details.id + '" class="accordion-group"><div class="accordion-heading">';
            markup += '<a class="accordion-toggle" data-toggle="collapse" data-parent="#serverDetailsForm" href="#' + domID + '">';
            // Accordian Holder for the server-name - updated by the user
            markup += '<span class="serverbox-name"></span>';
            markup += ' <span>' + details.type + '</span>';
            markup += ' </a>';
            markup += '</div><div id="' + domID + '" class="accordion-body collapse"><div class="accordion-inner">';
            markup += $formMarkup.html();
            markup += '</div></div></div>';

            var thisData = {
                uniqueID : details.id,
                server_name : details.name,
                vol_name : details.name,
                lb_name : details.name,
                server_cost : details.cost_per_hour || 0,
                monthly_cost : details.cost_per_month || 0,
                cloudProvider : details.cloudProvider,
                serviceID : window.plumCounter,
                serviceType : details.type,
                serviceTypeID : details.typeID
            }, dataNames = (formDataMapping[details.cloudProvider]||{})[details.type];
            for (var m = 0, n = dataNames.length; m < n; m++) {
                var thisVar = {};
                thisVar[dataNames[m]] = details[dataNames[m]]
                thisData = $.extend(thisData, thisVar);
            }

            $serverDetailsForm.append(Mustache.to_html(markup, thisData) || markup).show();

            if (!details.initialLoad) {
                // Scroll iff it's not the first load
                $server.trigger("click");
            }

            var $form = $("#" + domID);

            if (details.type === "CloudServer") {
                // Load the images dropdown
                getImages(details, $form);
            }

            // Now obsolete: Fix for FF
            /*$form.find(".accordion-body").on({
             shown : function() {
             $(this).css('overflow', 'visible');
             },
             hide : function() {
             $(this).css('overflow', 'hidden');
             }
             });*/

            $form.find('[name="server_name"],[name="vol_name"],[name="lb_name"]').on("change", function() {
                // Update the name on the canvas-box
                var $this = $(this), name = $this.val(), $accordian;
                $accordian = $this.closest(".accordion-group");
                $accordian.find(".serverbox-name").text(name);
                $server.find(".serverbox-name").text(name);
                $server.attr("data-name", name);
                $(".js-connections option[value='" + details.id + "']").text(name);
                // Update the choson dropdowns
                $(".js-connections").trigger("chosen:updated");
            }).trigger("change");

            $form.find('[name="no_instances"]').on("change", function() {
                // Update the count on the canvas-box
                var $this = $(this), value = $this.val();
                $server.find(".serverbox-count").text( value);
            }).trigger("change");

            $form.find(".js-connections").html(getConnectionOptions(details.type)).on('change', function() {
                // Update connections
                var value = $(this).val() || [];
                value = $.isArray(value) ? value : [value];
                setPlumConnections(plumbInstance, $server, value);
            });

            $form.find(".js-flavorId").html(getFlavorOptions(details.type, details.typeID, details.flavorID)).on('change', function() {
                // Update costs
                var $this = $(this), flavorID = $this.val(), flavor = getFlavorDetails(flavorID), $form = $this.closest(".accordion-group");
                $form.find('[name="monthly_cost"]').val(flavor.cost_per_month);
                $form.find('[name="server_cost"]').val(flavor.cost_per_hour);
                $server.find(".serverbox-flavor-name").text(flavor.name + " : ");
                updateSummary();
                //console.log("Selected flavor", flavorID, flavor, locallyGlobalStorage);
            }).trigger("change");

            $form.find('[name]').on("change", updateSummary);

            // Create connections
            setPlumConnections(plumbInstance, $server, details.connections, true);

            updateSummary();

        });

        // Add this as a connection option
        $(".js-connections").each(function() {
            var $this = $(this), $detailOption, type = $this.closest(".accordion-group").find("[name='serviceType']").val(), serverType = details.type;
            if (((allowableTargets[serverType] || []).indexOf(type) !== -1) || ((allowableTargets[type] || []).indexOf(serverType) !== -1)) {
                // if they can connect
                $detailOption = $this.find("option[data-id='" + details.id + "']")
                if ($detailOption.length === 0) {
                    $this.append('<option value="' + details.id + '">' + details.name + '</option>');
                } else {
                    $detailOption.text(detail.name);
                }
            }
        });
        // Update the choson dropdowns
        $(".js-connections").trigger("chosen:updated");

        /*if (!details.initialLoad) {
            // Scroll iff it's not the first load'
            scrollToElement($server);
        }*/
        updateSummary();
        updateWrapperHeight();
    }

    function removeServerPlum(plumbInstance, server) {
        // Delete a server plum
        var $server = $(server), serverID = $server.attr("data-id");
        plumbInstance.detachAllConnections($server);
        plumbInstance.removeAllEndpoints($server);
        $("#form_" + serverID + "_wrapper").remove();
        if ($serverDetailsForm.is(":empty")) {
            $serverDetailsForm.hide();
        }
        $(".js-connections option[value='" + serverID + "']").remove();
        // Update the choson dropdowns
        $(".js-connections").trigger("chosen:updated");
        $server.hide();
        updateSummary();
    }

    function editServerProperties() {
        // Show the form with the server's properties
        var $server = $(this), $form = $('a[href="#form_' + $server.attr("data-id") + '"]').trigger("click");
        scrollToElement($form);
    }

    function setPlumConnections(plumbInstance, source, targetIDs, saveConnections) {
        // Connect the given source with the given targets
        console.log("connecting", source, targetIDs);
        var $source = $(source);
        targetIDs = targetIDs || [];
        if (!saveConnections) {
            plumbInstance.detachAllConnections($source);
            $source.data("connections", []);
        }
        locallyGlobalStorage.connectionEndpointStorage = locallyGlobalStorage.connectionEndpointStorage || {};
        for (var i = 0, j = targetIDs.length; i < j; i++) {
            var $target = $("#serverItem_" + targetIDs[i]);
            handlePlumConnection(plumbInstance, {
                source : $source,
                target : $target
            });
        }
    }

    function intersect_safe(a, b) {
        /* finds the intersection of
         * two arrays in a simple fashion.
         *
         * PARAMS
         *  a - first array, must already be sorted
         *  b - second array, must already be sorted
         *
         * NOTES
         *
         *  Should have O(n) operations, where n is
         *    n = MIN(a.length(), b.length())
         */
        var ai = 0, bi = 0;
        var result = new Array();

        while (ai < a.length && bi < b.length) {
            if (a[ai] < b[bi]) {
                ai++;
            } else if (a[ai] > b[bi]) {
                bi++;
            } else/* they're equal */
            {
                result.push(a[ai]);
                ai++;
                bi++;
            }
        }

        return result;
    }

    function areConnected(plumbInstance, source, target) {
        return intersect_safe(plumbInstance.getAllConnections(source), plumbInstance.getAllConnections(target)).length !== 0
    }

    function getEndpointPosition($server, endpoint) {
        // Get the data label for the given endpoint
        switch(endpoint) {
            case $server.data("endpoint-top"):
                return "endpoint-top";
            case $server.data("endpoint-right"):
                return "endpoint-right";
            case $server.data("endpoint-left"):
                return "endpoint-left";
            case $server.data("endpoint-bottom"):
            default:
                return "endpoint-bottom";
        }
    }

    function handlePlumConnection(plumbInstance, info) {
        var $source = $(info.source), $target = $(info.target), sourceType, targetType, forwardConnectionPossible, reverseConnectionPossible, conSource, conTarget, endPoints, endPointFormat;
        sourceType = $source.attr("data-type");
        targetType = $target.attr("data-type");
        forwardConnectionPossible = (allowableTargets[sourceType] || []).indexOf(targetType) !== -1;
        reverseConnectionPossible = (allowableTargets[targetType] || []).indexOf(sourceType) !== -1;
        console.log("Connecting", info, sourceType, targetType, forwardConnectionPossible && connectionCustomizations[sourceType + ":" + targetType], reverseConnectionPossible && connectionCustomizations[targetType + ":" + sourceType]);

        locallyGlobalStorage.connectionEndpointStorage = locallyGlobalStorage.connectionEndpointStorage || {};

        try {
            // Disconnect the connection to customize it upon re-connect
            plumbInstance.detach(info.connection, {
                fireEvent : false
            });
        } catch(err) {
            console.warn("handlePlumConnection: Couldn't detach connection", err.message, err.stack);
        }

        var sourceConnections = $source.data("connections") || [];
        var targetConnections = $target.data("connections") || [];

        if (areConnected(plumbInstance, info.source, info.target)) {
            console.warn("handlePlumConnection: They are already connected");
            return;
        }

        if ((connectionLimitMapping[sourceType] < 0 || sourceConnections.length <= (connectionLimitMapping[sourceType] || Infinity)) && (connectionLimitMapping[targetType] < 0 || targetConnections.length <= (connectionLimitMapping[targetType] || Infinity))) {
            if (forwardConnectionPossible) {
                // If a connection is possible in the forward direction, make it happen
                endPointFormat = $source.attr("data-id") + ":" + $target.attr("data-id");
                endPoints = locallyGlobalStorage.connectionEndpointStorage[endPointFormat] || {};
                info = $.extend({
                    sourceEndpoint : $source.data(endPoints[$source.attr("data-id")] || "none"),
                    targetEndpoint : $target.data(endPoints[$target.attr("data-id")] || "none")
                }, info);
                plumbInstance.connect($.extend(true, {
                    source : info.sourceEndpoint || $source,
                    target : info.targetEndpoint || $target,
                    fireEvent : false
                }, connectionOptions, connectionCustomizations[sourceType + ":" + targetType] || {
                    overlays : false // Remove arrows when direction rule is missing
                })).setPaintStyle($.extend(true, {}, connectionOptions || {}, connectionCustomizations[sourceType + ":" + targetType] || {}).paintStyle);

                locallyGlobalStorage.connectionEndpointStorage[endPointFormat] = {};
                locallyGlobalStorage.connectionEndpointStorage[endPointFormat][$source.attr("data-id")] = getEndpointPosition($source, info.sourceEndpoint);
                locallyGlobalStorage.connectionEndpointStorage[endPointFormat][$target.attr("data-id")] = getEndpointPosition($target, info.targetEndpoint);

            } else if (reverseConnectionPossible) {
                // If a connection is possible in the reverse direction, make it happen
                endPointFormat = $target.attr("data-id") + ":" + $source.attr("data-id");
                endPoints = locallyGlobalStorage.connectionEndpointStorage[endPointFormat] || {};
                info = $.extend({
                    sourceEndpoint : $source.data(endPoints[$source.attr("data-id")] || "none"),
                    targetEndpoint : $target.data(endPoints[$target.attr("data-id")] || "none")
                }, info);
                plumbInstance.connect($.extend(true, {
                    source : info.targetEndpoint || $target,
                    target : info.sourceEndpoint || $source,
                    fireEvent : false
                }, connectionOptions, connectionCustomizations[targetType + ":" + sourceType] || {
                    overlays : false // Remove arrows when direction rule is missing
                })).setPaintStyle($.extend(true, {}, connectionOptions || {}, connectionCustomizations[targetType + ":" + sourceType] || {}).paintStyle);

                locallyGlobalStorage.connectionEndpointStorage[endPointFormat] = {};
                locallyGlobalStorage.connectionEndpointStorage[endPointFormat][$source.attr("data-id")] = getEndpointPosition($source, info.sourceEndpoint);
                locallyGlobalStorage.connectionEndpointStorage[endPointFormat][$target.attr("data-id")] = getEndpointPosition($target, info.targetEndpoint);

            }
            // Save source's connections
            if ($.inArray($target.attr("data-id"), sourceConnections) === -1) {
                sourceConnections.push($target.attr("data-id"));
            }
            $source.data("connections", sourceConnections);
            // Save target's connections
            if ($.inArray($source.attr("data-id"), targetConnections) === -1) {
                targetConnections.push($source.attr("data-id"));
            }
            $target.data("connections", targetConnections);

            $("#form_" + $target.attr("data-id") + "_wrapper").find(".js-connections").val(targetConnections).trigger("chosen:updated");
            $("#form_" + $source.attr("data-id") + "_wrapper").find(".js-connections").val(sourceConnections).trigger("chosen:updated");

        } else {
            console.warn("handlePlumConnection: Too many connections");
        }
    }

    function handlePlumDisConnection(plumbInstance, info) {
        // Handle the disconnection of plums
        var $source = $(info.source), $target = $(info.target), sourceType, targetType;

        console.log("Dis-Connecting", info, sourceType, targetType);

        // Update source's connections
        var sourceConnections = $source.data("connections") || [];
        sourceConnections.splice($.inArray($target.attr("data-id"), sourceConnections), 1);
        $source.data("connections", sourceConnections);
        $("#form_" + $source.attr("data-id") + "_wrapper").find(".js-connections").val(sourceConnections).trigger("chosen:updated");

        // Update target's connections
        var targetConnections = $target.data("connections") || [];
        targetConnections.splice($.inArray($source.attr("data-id"), targetConnections), 1);
        $target.data("connections", targetConnections);
        $("#form_" + $target.attr("data-id") + "_wrapper").find(".js-connections").val(targetConnections).trigger("chosen:updated");

        locallyGlobalStorage.connectionEndpointStorage[$source.attr("data-id") + ":" + $target.attr("data-id")] = null;
    }

    function getConnectionConfiguration(cloudProvider) {
        $.post(baseURL + "/admin/dplanner/getDeploymentPlannerConnections", {
            cloudProvider : cloudProvider || "Rackspace Cloud"
        }, function(response) {
            console.log("getConnectionConfiguration", response);
            if (response.status === "OK") {
                response.message = response.message || [];
                for (var i = 0, len = response.message.length; i < len; i++) {
                    var item = response.message[i];
                    // Update the global configuration maps
                    connectionLimitMapping[item.serverType] = parseInt(item.maxConnections, 10);
                    allowableTargets[item.serverType] = JSON.parse(item.allowableTargets || "[]");
                    formDataMapping[item.cloudProvider] = formDataMapping[item.cloudProvider] || {};
                    formDataMapping[item.cloudProvider][item.serverType] = JSON.parse(item.additionalFormDataFields || "[]");
                    serverIconMap[item.serverType] = baseURL + item.serverIcon;
                    serverBackgroundMap[item.serverType] = baseURL + item.serverBackground;
                }
            } else {
                $.pnotify({
                    title : 'Error obtaining connection configuration',
                    text : response.message,
                    icon : 'icon-error-sign',
                    opacity : .8
                });
            }
        }, "json");
    }

    function getCloudProviderDetails(cloudProvider, region) {
        $.post(baseURL + "/admin/dplanner/getServiceTypesAndFlavors", {
            cloudProvider : cloudProvider || "Rackspace Cloud",
            region : region || "US"
        }, function(response) {
            console.log("getCloudProviderDetails", response);
            if (response.status === "OK") {
                var serviceTypes, serviceFlavors, $serviceAccordianWrapper, accordianMarkup, i, j;

                locallyGlobalStorage["serviceTypes"] = serviceTypes = response.message.serviceTypes;
                locallyGlobalStorage["serviceFlavors"] = serviceFlavors = response.message.serviceFlavors;

                $serviceAccordianWrapper = $("#serviceAccordianWrapper").html("");

                accordianMarkup = "";

                var iconMapping, iconURL;
                iconMapping = serverIconMap;

                accordianMarkup += '<ol class="nav nav-pills">';
                for ( i = 0, j = serviceTypes.length; i < j; i++) {
                    var serviceType = serviceTypes[i], thisID = 'serviceType_' + serviceType.id, m, n;

                    accordianMarkup += '<li><a class="dragndrop-server" data-type="' + serviceType.title + '" data-id="' + serviceType.id + '">';
                    iconURL = iconMapping[serviceType.title] || iconMapping["fallback"];
                    accordianMarkup += '<img src="' + iconURL + '" style="max-height: 24px; margin: 5px;" />';
                    accordianMarkup += serviceType.title;
                    accordianMarkup += '</a></li>';
                }
                accordianMarkup += '</ol>';

                $("#serviceAccordianWrapper").html(accordianMarkup);

                makeServersDraggable();

                updateWrapperHeight();
            }
        }, "json");
    }

    function makeServersDraggable() {
        // Make them draggable
        $(".dragndrop-server").draggable({
            revert : true,
            start : function(event, ui) {
                console.log("Start Drag:", this, arguments);
            },
            stop : function(event, ui) {
                console.log("End Drag:", this, arguments);
            }
        });
    }

    function getBudgetInfo(budgetID) {
        // Get budget info
        if (!budgetID) {
            console.error("Invalid budgetID given:", budgetID);
            return false;
        }
        $.post(baseURL + "/business_metadata/admin/budgets/getBudget", {
            budgetId : budgetID
        }, function(response) {
            // Save in locally-global variable
            if (isString(response)) {
                //@TODO: this should never happen: Check backend
                response = JSON.parse(response);
            }
            budget = {
                hard : parseFloat(response.hard),
                soft : parseFloat(response.soft)
            };
            updateSummary();
            console.log("budget", budget, response);
        });
    }

    function updateSummary() {
        // Calculate cost summary - Uses Brute Force
        var hourlySum = 0, monthlySum = 0;

        console.log("Updating summary");

        $('input[name="server_cost"]').each(function() {
            var $this = $(this), rate = parseFloat($this.val()) || 0, $form = $this.closest(".accordion-group"), count = parseFloat($form.find('[name="no_instances"]').val()) || 1;
            hourlySum += rate * count;
            //console.log(this, rate, count, $form);
        });
        $("#serverSummaryBox_hourly").text(hourlySum.toFixed(2));

        $('input[name="monthly_cost"]').each(function() {
            var $this, $form, rate, count, sizeCost, vol_sizeFactor, vol_sizeCost, vol_costFactor;
            $this = $(this);
            rate = parseFloat($this.val()) || 0;
            $form = $this.closest(".accordion-group");
            count = parseFloat($form.find('[name="no_instances"]').val()) || 1;
            sizeCost = (parseFloat($form.find('[name="disk_size"]').val()) || 0) * 0.75;
            vol_sizeFactor = ((parseFloat($form.find('[name="vol_size"]').val()) / 100) || 0);
            vol_costFactor = ((getFlavorDetails($form.find('[name="flavorId"]').val()).name || "").toLowerCase().indexOf("SSD") > -1) ? 70 : 15;
            vol_sizeCost = vol_sizeFactor * vol_costFactor;
            monthlySum += (rate * count) + sizeCost + vol_sizeCost;
            //console.log(this, rate, count, sizeCost, $form);
        });
        $("#serverSummaryBox_monthly").text(monthlySum.toFixed(2));

        if (budget) {
            var hard = (monthlySum / budget.hard * 100) || 0, soft = (monthlySum / budget.soft * 100) || 0;
            var $soft = $("#serverSummaryBox_budget_soft").text(soft.toFixed(2));
            if (soft >= 100) {
                $soft.addClass("alert-error");
            } else {
                $soft.removeClass("alert-error");
            }
            var $hard = $("#serverSummaryBox_budget_hard").text(hard.toFixed(2));
            if (hard >= 100) {
                $hard.addClass("alert-error");
            } else {
                $hard.removeClass("alert-error");
            }
        }
    }

    function getNamesForIDs(ids) {
        // Return an array of names/titles for the given ids
        var names = [];
        ids = ids || [];
        for (var i = 0, j = ids.length; i < j; i++) {
            var $this = $("#serverItem_" + ids[i]);
            names.push($this.attr("data-name") || $this.attr("data-title") || $this.text() || "");
        }
        return names;
    }

    function saveFormData(e, status) {
        var rawData = $("#form_createedit").serializeObject(), data = {}, i, j, uniqueFieldTypeCount = {};
        console.log("Form Data", rawData);
        data[rawData.title] = {};
        if (rawData.uniqueID) {
            if (isString(rawData.uniqueID)) {
                // If there is just a single item
                var $server = $("#serverItem_" + rawData.uniqueID), thisData = {
                    uniqueID : rawData.uniqueID,
                    flavorId : rawData.flavorId,
                    flavorName : getFlavorDetails(rawData.flavorId).name,
                    cloudProvider : rawData.cloudProvider[1],
                    serviceType : rawData.serviceType,
                    serviceTypeID : rawData.serviceTypeID,
                    position : $server.position(),
                    connections : $server.data("connections"),
                    connection_names : getNamesForIDs($server.data("connections"))
                }, dataNames = formDataMapping[rawData.cloudProvider[1]][rawData.serviceType];
                for (var m = 0, n = dataNames.length; m < n; m++) {
                    var thisVar = {};
                    thisVar[dataNames[m]] = rawData[dataNames[m]];
                    thisData = $.extend(thisData, thisVar);
                }
                data[rawData.title][rawData.uniqueID] = thisData;
            } else {
                // If there are more than one items
                for ( i = 0, j = rawData.uniqueID.length; i < j; i++) {
                    var $server = $("#serverItem_" + rawData.uniqueID[i]), thisData = {
                        uniqueID : rawData.uniqueID[i],
                        flavorId : rawData.flavorId[i],
                        flavorName : getFlavorDetails(rawData.flavorId[i]).name,
                        cloudProvider : rawData.cloudProvider[i + 1],
                        serviceType : rawData.serviceType[i],
                        serviceTypeID : rawData.serviceTypeID[i],
                        position : $server.position(),
                        connections : $server.data("connections"),
                        connection_names : getNamesForIDs($server.data("connections"))
                    }, dataNames = formDataMapping[rawData.cloudProvider[i+1]][rawData.serviceType[i]];
                    for (var m = 0, n = dataNames.length; m < n; m++) {
                        var thisVar = {}, name = dataNames[m], fieldData = rawData[name];
                        if ($.isArray(fieldData)) {
                            // If there are more than one fields with this name
                            uniqueFieldTypeCount[name] = uniqueFieldTypeCount[name] || 0;
                            thisVar[name] = rawData[name][uniqueFieldTypeCount[name]];
                            uniqueFieldTypeCount[name] += 1;
                        } else {
                            // Just one field
                            thisVar[name] = rawData[name];
                        }
                        thisData = $.extend(thisData, thisVar);
                    }
                    data[rawData.title][rawData.uniqueID[i]] = thisData;
                }
            }
        }

        $.ajaxSetup({
            data : {
                csrf_test_name : $.cookie('csrf_cookie_name')
            }
        });

        var summaryData = {
            hourly : $("#serverSummaryBox_hourly").text(),
            monthly : $("#serverSummaryBox_monthly").text(),
            budget_soft : $("#serverSummaryBox_budget_soft").text(),
            budget_hard : $("#serverSummaryBox_budget_hard").text()
        };

        console.log("Parsed data", data);

        $.post(baseURL + "/admin/dplanner/saveData", {
            data : JSON.stringify(data),
            title : rawData.title,
            id : rawData.id,
            tags : rawData.tags,
            cloudProvider : $("#cloudProviderWrapper select").val(),
            region : $("#regionWrapper select").val(),
            deploymentMode : $("#deploymentModeWrapper select").val(),
            securityGroupId : $("#securityGroupIdWrapper select").val(),
            budgetId : $("#budgetIdWrapper select").val(),
            summary : JSON.stringify(summaryData),
            status : status || "draft",
            connectionEndpointStorage : JSON.stringify(locallyGlobalStorage.connectionEndpointStorage)
        }, function(response) {
            //console.log("Saved data", response);
            var json = $.parseJSON(response)
            if (json.status == 'OK') {
                $.pnotify({
                    title : 'Submit Notice',
                    text : json.message + ':' + rawData.title,
                    icon : 'icon-success-sign',
                    opacity : .8
                });
                if (status) {
                    deploymentStatusAction(status);
                }
            } else if (json.status == 'error') {
                $.pnotify({
                    title : 'Submit Notice',
                    text : json.message + ':' + rawData.title,
                    icon : 'icon-error-sign',
                    opacity : .8
                });
            }
            console.log("Server status", status);
            if (!status) {
                // If we didn't set a status, assume its a save and exit
                window.location = baseURL + "/admin/dplanner/index";
            } else {
                // Show status instead of redirecting directly
                getDeploymentStatus();
            }
        });
    }

    function displayDeploymentInProgress() {
        //Display the progressbar
        var $container = $("#form_createedit"), containerOffset = $container.offset(), $deploymentProgressOverlay = $("#deploymentProgressOverlay");
        $deploymentProgressOverlay.show().css({
            top : containerOffset.top,
            left : containerOffset.left,
            height : $container.height(),
            width : $container.width()
        });
        makeReadOnly();
        // Keep pinging the server for status
        deploymentStatusTimeout = setInterval(getDeploymentStatus, 5000);
    }

    function deploymentStatusAction(status) {
        // Perform an action depending on the status
        switch (status) {
            case "in progress":
                displayDeploymentInProgress();
                break;
            case "completed":
                makeReadOnly();
                break;
        }
    }

    function getDeploymentStatus() {
        // Get deployment status
        deploymentStatusTimeout && clearInterval(deploymentStatusTimeout);
        $.post(baseURL + "/admin/dplanner/getStatus", {
            deployment_id : $("#id").val()
        }, function(response) {
            console.log("getDeploymentStatus", response);
            if (response.status == 'OK') {
                deploymentStatusAction(response.message[0].status);
            }
        }, "json");
    }

    function makeReadOnly() {
        // Make the whole system read-only
        $("#serviceAccordian").hide();
        var $container = $("#form_createedit");
        // Diable inputs
        $container.find("input, textarea, select").prop("disabled", true).trigger("chosen:updated");
        // Disable chosen dropdowns
        $container.find("select.chosen-done").removeAttr("style", '').removeClass("chosen-done").data("chosen", null).next().remove();
        // Disable submit buttons
        $("#submitForm,#deploy").addClass("disabled").off("click");
        // Disable form submission
        $container.off("submit").on("submit", preventDefault);
        var $wrapper = $("#jsPlumbSuperWrapper");
        // Add an overlay to the plum wrapper to disable interaction
        $('<div/>').appendTo($wrapper).css({
            "position" : "absolute",
            "top" : "0",
            "left" : "0",
            "right" : "0",
            "bottom" : "0",
            "height" : "100%",
            "width" : "100%",
            "z-index" : 9999999,
            "background-color" : "rgba(245,245,245,0.3)"
        });
    }


    jsPlumb.ready(function() {
        //console.log("jsPlumb Ready");
        var plumbInstance, $wrapper;

        locallyGlobalStorage.connectionEndpointStorage = locallyGlobalStorage.connectionEndpointStorage || {};

        plumbInstance = jsPlumb.getInstance();
        plumbInstance.Defaults.Container = "jsPlumbSuperWrapper";

        // Setup on-connection handler
        plumbInstance.bind("jsPlumbConnection", function(info) {
            handlePlumConnection(plumbInstance, info);
        });

        plumbInstance.bind("connectionDetached ", function(info) {
            handlePlumDisConnection(plumbInstance, info);
        });

        var removeConnection = function(info) {
            // Remove all connections
            console.log("Attempting to remove connection", info);
            if (info.connections) {
                // Endpoint
                for (var i = 0, j = info.connections.length; i < j; i++) {
                    handlePlumDisConnection(plumbInstance, info.connections[i]);
                    plumbInstance.detach(info.connections[i]);
                }
            } else {
                handlePlumDisConnection(plumbInstance, info);
                plumbInstance.detach(info);
            }
        };

        // Setup handler for deleting connections on double click
        plumbInstance.bind("dblclick", removeConnection);

        // Setup handler for deleting connections on double clicking the endpoint
        plumbInstance.bind("endpointDblClick", removeConnection);

        $wrapper = $("#jsPlumbSuperWrapper");

        // Repaint when in view
        $('a[data-toggle="tab"]').on('shown', plumbInstance.repaintEverything);

        makeServersDraggable();

        $serverDetailsForm = $("#serverDetailsForm").html("");

        $wrapper.droppable({
            accept : ".dragndrop-server",
            activeClass : "alert-info",
            hoverClass : "alert-success",
            drop : function(event, ui) {
                console.log("Dropped", ui.draggable);
                var $server = $(ui.draggable), serverOffset = $server.offset(), wrapperOffset = $wrapper.offset(), plumPosition = {
                    top : Math.max(serverOffset.top - wrapperOffset.top, 0),
                    left : Math.max(serverOffset.left - wrapperOffset.left, 0)
                };

                console.log("Dropped plum", $server, serverOffset, wrapperOffset, plumPosition);

                createServerPlum(plumbInstance, $wrapper, {
                    type : $server.attr("data-type"),
                    typeID : $server.attr("data-id"),
                    id : $server.attr("data-id") + "_" + parseInt(Math.random() * 100000, 10),
                    name : $server.attr("data-type"),
                    cloudProvider : $("#cloudProviderWrapper select").val(),
                    cost_per_hour : parseFloat($server.attr("data-cost_per_hour")),
                    cost_per_month : parseFloat($server.attr("data-cost_per_month")),
                    position : plumPosition
                });
            }
        });

        // Get deployment status
        // getDeploymentStatus();

        $("#cloudProviderWrapper select, #regionWrapper select").on("change", function() {
            // Get cloud provider details
            getCloudProviderDetails($("#cloudProviderWrapper select").val(), $("#regionWrapper select").val());
            getConnectionConfiguration($("#cloudProviderWrapper select").val());
        });
        getCloudProviderDetails($("#cloudProviderWrapper select").val(), $("#regionWrapper select").val());
        getConnectionConfiguration($("#cloudProviderWrapper select").val());

        $("#budgetIdWrapper select").on("change", function() {
            // Get budget Info
            getBudgetInfo($(this).val());
        });
        getBudgetInfo($("#budgetIdWrapper select").val());

        $(".toggle-left,.toggle-right").on("click", function() {
            var $this = $(this), $thisWrapper, $thisContent, $mainContent, isLeft = $this.hasClass("toggle-left");
            $thisWrapper = $this.closest(".toggle-wrapper");
            $thisContent = $thisWrapper.find(".toggle-content");
            $mainContent = $(".main-content-wrapper");

            if ($thisContent.is(":visible")) {
                //Hide the content
                $thisContent.fadeOut("fast");
                $thisWrapper.removeClass("span3").addClass("span0");
                // Expand middle content
                if ($mainContent.hasClass("span6")) {
                    $mainContent.removeClass("span6").addClass("span9");
                } else {
                    $mainContent.removeClass("span9").addClass("span11");
                }
                // Change arrow direction
                $this.html('<i class="icon-chevron-' + ( isLeft ? "right" : "left") + '" />');
            } else {
                //Show the content
                $thisContent.fadeIn("fast");
                $thisWrapper.removeClass("span0").addClass("span3");
                //Compress middle content
                if ($mainContent.hasClass("span9")) {
                    $mainContent.removeClass("span9").addClass("span6");
                } else {
                    $mainContent.removeClass("span11").addClass("span9");
                }
                // Change arrow direction
                $this.html('<i class="icon-chevron-' + (!isLeft ? "right" : "left") + '" />');
            }
            updateWrapperHeight();
        });

        // Allow the select to be shown completely
        /*$("#form_createedit").on("mouseover focus", ".chosen-container, select", function() {
        $(this).parents(".accordion-body").css("overflow", "visible");
        }).on("mouseout blur", ".chosen-container, select", function() {
        $(this).parents(".accordion-body").css("overflow", "hidden");
        });*/

        // Bruteforce accordions
        $("#form_createedit").on("click", ".accordion-toggle", function(e) {
            preventDefault(e);
            var $thisGroup = $(this).closest(".accordion-group");
            var $thisBody = $thisGroup.find(".accordion-body");
            //.collapse('toggle');
            if ($thisBody.hasClass("in")) {
                // If in, make it out
                $thisBody.css({
                    "height" : "0px",
                    "overflow" : "hidden"
                }).removeClass("in").addClass("out");
            } else {
                $thisBody.css({
                    "height" : "auto",
                    "overflow" : "visible"
                }).removeClass("out").addClass("in");
            }
            $thisGroup.siblings(".accordion-group").find(".accordion-body").css({
                "height" : "0px",
                "overflow" : "hidden"
            }).removeClass("in").addClass("out");
            //.collapse('hide');
            updateWrapperHeight();
            return false;
        });

        $("#form_createedit").on("submit", function(e, status) {
            preventDefault(e);
            if ($(this).valid()) {
                console.log("Form is valid, saving data");
                saveFormData.apply(this, arguments);
            } else {
                console.warn("Form is not valid");
                $.pnotify({
                    title : 'Form Validation failed',
                    text : 'Please check if all the required fields are filled and submit again',
                    icon : 'icon-error-sign',
                    opacity : .8
                });
            }
            return false;
        }).validate();

        $("#submitForm").on("click", function() {
            console.log("Triggering form submission");
            $("#form_createedit").trigger("submit");
        });

        $("#simulate").on("click", function() {
            console.log("Triggering Simulate");
            // $("#form_createedit").trigger("submit");
        });

        $("#deploy").on("click", function() {
            console.log("Triggering form submission for deployment");
            $("#form_createedit").trigger("submit", "in progress");
        });

        $("#regionWrapper select").on("change", function() {
            // Change the currency fields
            var currency = regionToCurrencyMapping[$(this).val() || "US"];
            $(".symbol-currency").each(function() {
                $(this).text(currency);
            });
        });

        if (window.deploymentData && window.deploymentData.data) {
            // If there is data for the modules, load them up
            var data = JSON.parse(window.deploymentData.data);
            //console.log("Stored data", data);
            deploymentStatusAction(deploymentData.status);
            locallyGlobalStorage.connectionEndpointStorage = $.parseJSON(deploymentData.connectionEndpointStorage || "{}");
            for (var deployment in data) {
                if (data.hasOwnProperty(deployment)) {
                    var services = data[deployment];
                    for (var serviceName in services) {
                        if (services.hasOwnProperty(serviceName)) {
                            var service = services[serviceName], thisData = {
                                flavorID : service.flavorId,
                                flavorName : service.flavorName,
                                cloudProvider : service.cloudProvider,
                                type : service.serviceType,
                                typeID : service.serviceTypeID,
                                cost_per_hour : parseFloat(service.server_cost),
                                cost_per_month : parseFloat(service.monthly_cost),
                                position : service.position,
                                id : service.uniqueID || (service.flavorId + "_" + parseInt(Math.random() * 100000, 10)),
                                connections : service.connections,
                                initialLoad : true
                            }, dataNames = formDataMapping[service.cloudProvider][service.serviceType];
                            for (var m = 0, n = dataNames.length; m < n; m++) {
                                // Load up the type specific attributes
                                var thisVar = {};
                                thisVar[dataNames[m]] = service[dataNames[m]]
                                thisData = $.extend(thisData, thisVar);
                            }
                            createServerPlum(plumbInstance, $wrapper, thisData);
                        }
                    }
                }
            }
            deploymentStatusAction(deploymentData.status);
        }

    });
})(jQuery);
