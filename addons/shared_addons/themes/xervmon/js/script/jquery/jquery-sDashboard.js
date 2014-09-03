;/*
 * jquery sDashboard (2.5-1.0.0)
 * Copyright 2012, Model N, Inc
 * Modified by Doers' Guild for Xervmon
 * Distributed under MIT license.
 * https://github.com/ModelN/sDashboard
 */

( function(factory) {"use strict";
        if ( typeof define === 'function' && define.amd) {
            // Register as an AMD module if available...
            define(['jquery', 'Flotr'], factory);
        } else {
            // Browser globals for the un-enlightened...
            factory($, Flotr);
        }
    }(function($, Flotr) {"use strict";

        $.widget("mn.sDashboard", {
            version : "2.5-1.0.0",
            options : {
                dashboardData : []
            },
            _create : function() {
                this.element.addClass("sDashboard");
                this._createView();

            },
            _setOption : function(key, value) {
                this.options[key] = value;
                if (key === "dashboardData") {
                    this._createView();
                }
            },

            _createView : function() {
                var _dashboardData = this.options.dashboardData;
                var i;
                for ( i = 0; i < _dashboardData.length; i++) {
                    var widget = this._constructWidget(_dashboardData[i]);
                    //append the widget to the dashboard
                    this.element.append(widget);
                    this._renderChart(_dashboardData[i]);
                }

                var that = this;
                //call the jquery ui sortable on the columns
                this.element.sortable({
                    handle : ".sDashboardWidgetHeader",
                    update : function(event, ui) {
                        var sortOrderArray = $(this).sortable('toArray');
                        var sortedDefinitions = [];
                        for ( i = 0; i < sortOrderArray.length; i++) {
                            var widgetContent = that._getWidgetContentForId(sortOrderArray[i], that);
                            sortedDefinitions.push(widgetContent);
                        }

                        if (sortedDefinitions.length > 0) {
                            var evtData = {
                                sortedDefinitions : sortedDefinitions
                            };
                            that._trigger("orderchanged", null, evtData);
                        }

                    }
                });

                var disableSelection = this.options.hasOwnProperty("disableSelection") ? this.options.disableSelection : true;
                if (disableSelection) {
                    this.element.disableSelection();
                }
                //bind events for widgets
                this._bindEvents();

                //trigger creation complete when the dashboard widgets are constructed
                this._trigger("creationComplete", null);

            },
            _getWidgetContentForId : function(id, context) {
                var widgetData = context.getDashboardData();
                for (var i = 0; i < widgetData.length; i++) {
                    var widgetObject = widgetData[i];
                    if (widgetObject.widgetId === id) {
                        return widgetObject;
                    }
                }
                return [];
            },
            _bindEvents : function() {
                var self = this;
                //click event for maximize button
                this.element.on("click", ".sDashboardWidgetHeader .js-plus-button", function(e) {
                   $(".navbar-fixed-top").css('z-index','9');
                    //get the widget List Item Dom
                    var widgetListItem = $(e.currentTarget).parents("li:first");
                    //get the widget Container
                    var widget = $(e.currentTarget).parents(".sDashboardWidget:first");
                    //get the widget Content
                    var widgetContainer = widget.find(".sDashboardWidgetContent");

                    var widgetDefinition = self._getWidgetContentForId(widgetListItem.attr("id"), self);

                    //toggle the maximize icon into minimize icon
                    //change the tooltip on the maximize/minimize icon buttons
                    if ($(e.currentTarget).attr("title") === "Maximize") {
						//  $(".navbar-fixed-top").css('z-index','1030');
                        $(e.currentTarget).attr("title", "Minimize").addClass("icon-minus-sign").removeClass("icon-plus-sign");
                        self._trigger("widgetMaximized", null, {
                            "widgetDefinition" : widgetDefinition
                        });
                    } else {
                        $(e.currentTarget).attr("title", "Maximize").removeClass("icon-minus-sign").addClass("icon-plus-sign");
						$(".navbar-fixed-top").css('z-index','1030');
                        self._trigger("widgetMinimized", null, {
                            "widgetDefinition" : widgetDefinition
                        });
                    }

                    //toggle the class for widget and inner container
                    widget.toggleClass("sDashboardWidgetContainerMaximized");
                    widgetContainer.toggleClass("sDashboardWidgetContentMaximized ");

                    if (widgetDefinition.widgetType === "chart") {
                        var chartArea = widgetContainer.find(" div.sDashboardChart");
                        Flotr.draw(chartArea[0], widgetDefinition.widgetContent.data, widgetDefinition.widgetContent.options);
                        if (!widgetDefinition.getDataBySelection) {
                            //when redrawing the widget, the click event listner is getting destroyed, we need to re-register it here again
                            //need to find out if its a bug on flotr2 library.
                            self._bindChartEvents(chartArea[0], widgetListItem.attr("id"), widgetDefinition, self);
                        }
                    }
                });

                //refresh widget click event handler
                this.element.on("click", ".sDashboardWidgetHeader .js-refresh-button", function(e) {
                    var widget = $(e.currentTarget).parents("li:first");
                    var widgetId = widget.attr("id");
                    var widgetDefinition = self._getWidgetContentForId(widgetId, self);
                    var refreshedData = widgetDefinition.refreshCallBack.apply(self, [widgetId]);
                    widgetDefinition.widgetContent = refreshedData;
                    if (widgetDefinition.widgetType === 'chart') {
                        self._renderChart(widgetDefinition);
                    } else if (widgetDefinition.widgetType === 'table') {
                        self._refreshTable(widgetDefinition, widget);
                    } else {
                        self._refreshRegularWidget(widgetDefinition, widget);
                    }

                });

                //delete widget by clicking the 'x' icon on the widget
                this.element.on("click", ".sDashboardWidgetHeader .js-close-button", function(e) {
                    var widget = $(e.currentTarget).parents("li:first");
                    var widgetId = widget.attr("id");
                    //show hide effect
                    widget.hide("fold", {}, 300);
                    widget.remove();
                    self._removeWidgetFromWidgetDefinitions(widgetId);
                });
				
				 //hide widget content by clicking the 'v' icon on the widget
                this.element.on("click", ".sDashboardWidgetHeader .js-hide-button", function(e) {
				$(this).parents('.sDashboardWidget ').find('.sDashboardWidgetContent').hide();
				$(this).parent().css('border-radius','10px 10px 10px 10px');
				$(this).attr('title','show');
                $(this).addClass('js-show-button'); 
				$(this).removeClass('js-hide-button'); 
                   
                });
				this.element.on("click", ".sDashboardWidgetHeader .js-show-button", function(e) {
				$(this).parents('.sDashboardWidget ').find('.sDashboardWidgetContent').show();
				$(this).parent().css('border-radius','10px 10px 0px 0px');
				$(this).attr('title','hide');
                $(this).removeClass('js-show-button'); 
				$(this).addClass('js-hide-button');
                   
                });

                //table row click
                this.element.on("click", ".sDashboardWidgetContent table.sDashboardTableView tbody tr", function(e) {
                    var selectedRow = $(e.currentTarget);

                    if (selectedRow.length > 0) {
                        var selectedDataTable = selectedRow.parents('table:first').dataTable();

                        var selectedWidget = selectedRow.parents("li:first");
                        var selectedRowData = selectedDataTable.fnGetData(selectedRow[0]);
                        var selectedWidgetId = selectedWidget.attr("id");
                        var evtData = {
                            selectedRowData : selectedRowData,
                            selectedWidgetId : selectedWidgetId
                        };

                        //trigger dashboardTableViewRowClick changed event
                        self._trigger("rowclicked", null, evtData);
                    }
                });
            },

            _constructWidget : function(widgetDefinition) {

                //create an outer list item
                var widget = $("<li/>").attr("id", widgetDefinition.widgetId);
                //create a widget container
                var widgetContainer = $("<div/>").addClass("sDashboardWidget ui-widget ui-widget-content ui-helper-clearfix accordion-group ").css("background", "white");

                //create a widget header
                var widgetHeader = $("<h4/>").addClass("sDashboardWidgetHeader ui-widget-header accordion-heading").css({
                    "display" : "block",
                    "margin" : 0,
                    "padding" : "5px 11px"
                });
                var maximizeButton = $('<i title="Maximize" class="js-plus-button icon-plus-sign pull-right"></i>');

                var deleteButton = $('<i title="Close" class="js-close-button icon-remove pull-right"></i>');

                var menuButton = $('<i title="Options" class="js-menu-button icon-th-list pull-right"></i>');
				
				var hideButton = $('<i title="hide" class="js-hide-button icon-hide pull-right"></i>');
				
				var widgetIcon= $('<i class="widgetIcon pull-left"></i>');
				
				var tooltiptIcon= $('<i  title="widget details" class="tooltiptIcon pull-right">?</i>');

                //add delete button
                widgetHeader.append(deleteButton);
                //add Maximizebutton
                widgetHeader.append(maximizeButton);
                //add hidebutton
                widgetHeader.append(hideButton);
                //add menu button
                widgetHeader.append(menuButton);
				//add widgetIcon
                widgetHeader.append(widgetIcon);
				//add tooltipIcon
                widgetHeader.append(tooltiptIcon);

                if (widgetDefinition.hasOwnProperty("enableRefresh") && widgetDefinition.enableRefresh) {
                    var refreshButton = $('<i title="Refresh" class="js-refresh-button icon-refresh pull-right"></i>');
                    //add refresh button
                    widgetHeader.append(refreshButton);
                }

                //add widget title
                widgetHeader.append(widgetDefinition.widgetTitle);

                //create a widget content
                var widgetContent = $("<div/>").addClass("sDashboardWidgetContent accordion-body collapse in");

                if (widgetDefinition.widgetType === 'table') {
                    var tableDef = {
                        "aaData" : widgetDefinition.widgetContent.aaData,
                        "aoColumns" : widgetDefinition.widgetContent.aoColumns
                    };
                    if (widgetDefinition.setJqueryStyle) {
                        tableDef["bJQueryUI"] = true;
                    }

                    var dataTable = $('<table cellpadding="0" cellspacing="0" border="0" class="display sDashboardTableView table table-bordered"></table>').dataTable(tableDef);
                    widgetContent.append(dataTable);
                } else if (widgetDefinition.widgetType === 'chart') {
                    var chart = $('<div/>').addClass("sDashboardChart");
                    if (widgetDefinition.getDataBySelection) {
                        chart.addClass("sDashboardChartSelectable");
                    } else {
                        chart.addClass("sDashboardChartClickable");
                    }
                    widgetContent.append(chart);
                } else {
                    widgetContent.append(widgetDefinition.widgetContent);
                }

                //add widgetHeader to widgetContainer
                widgetContainer.append(widgetHeader);
                //add widgetContent to widgetContainer
                widgetContainer.append(widgetContent);

                //append the widgetContainer to the widget
                widget.append(widgetContainer);

                //return widget
                return widget;
            },
            _refreshRegularWidget : function(widgetDefinition, widget) {
                var isMaximized = widget.find(".sDashboardWidgetContent").hasClass('sDashboardWidgetContentMaximized');
                //first remove the content
                widget.find('.sDashboardWidgetContent').empty().remove();
                //then create the content again
                var widgetContent = $("<div/>").addClass("sDashboardWidgetContent");
                //if its maximized add the maximized class
                if (isMaximized) {
                    widgetContent.addClass('sDashboardWidgetContentMaximized');
                }
                widgetContent.append(widgetDefinition.widgetContent);
                //then append this to the widget again;
                widget.find(".sDashboardWidget").append(widgetContent);
            },
            _refreshTable : function(widgetDefinition, widget) {
                var selectedDataTable = widget.find('table:first').dataTable();
                selectedDataTable.fnClearTable();
                selectedDataTable.fnAddData(widgetDefinition.widgetContent["aaData"]);

            },
            _renderChart : function(widgetDefinition) {
                var id = "li#" + widgetDefinition.widgetId;
                var chartArea;
                var data;
                var options;

                if (widgetDefinition.widgetType === 'chart') {
                    chartArea = this.element.find(id + " div.sDashboardChart");
                    data = widgetDefinition.widgetContent.data;
                    options = widgetDefinition.widgetContent.options;
                    Flotr.draw(chartArea[0], data, options);
                    if (widgetDefinition.getDataBySelection) {
                        this._bindSelectEvent(chartArea[0], widgetDefinition.widgetId, widgetDefinition, this);
                    } else {
                        this._bindChartEvents(chartArea[0], widgetDefinition.widgetId, widgetDefinition, this);
                    }
                }

            },
            _bindSelectEvent : function(chartArea, widgetId, widgetDefinition, context) {
                Flotr.EventAdapter.observe(chartArea, "flotr:select", function(area) {
                    var evtObj = {
                        selectedWidgetId : widgetId,
                        chartData : area
                    };
                    context._trigger("plotselected", null, evtObj);
                });
            },
            _bindChartEvents : function(chartArea, widgetId, widgetDefinition, context) {

                Flotr.EventAdapter.observe(chartArea, 'flotr:click', function(d) {
                    //only if a series is clicked dispatch a click event
                    if (d.index !== undefined && d.seriesIndex !== undefined) {
                        var evtObj = {};
                        evtObj.selectedWidgetId = widgetId;
                        evtObj.flotr2GeneratedData = d;
                        var widgetData = widgetDefinition.widgetContent.data;
                        var seriesData = widgetData[d.seriesIndex];
                        var selectedData;

                        if ($.isArray(seriesData)) {
                            selectedData = seriesData[d.index];
                        } else {
                            selectedData = seriesData;
                        }

                        evtObj.customData = {
                            index : d.index,
                            selectedIndex : d.seriesIndex,
                            seriesData : seriesData,
                            selectedData : selectedData
                        };
                        context._trigger("plotclicked", null, evtObj);
                    }
                });

            },
            _removeWidgetFromWidgetDefinitions : function(widgetId) {
                var widgetDefs = this.options.dashboardData;
                for (var i in widgetDefs) {
                    var currentWidgetId = widgetDefs[i].widgetId;
                    if (currentWidgetId === widgetId) {
                        widgetDefs.splice(i, 1);
                        break;
                    }
                }
            },

            _ifWidgetAlreadyExists : function(widgetId) {
                if (!widgetId) {
                    throw "Expected widgetId to be defined";
                }
                var idSelector = "#" + widgetId;
                //get the dom element
                var widget = this.element.find("li" + idSelector);
                if (widget.length > 0) {
                    return true;
                }
                return false;
            },

            /*public methods*/
            //add a widget to the dashbaord
            addWidget : function(widgetDefinition) {
                if (!widgetDefinition.widgetId) {
                    throw "Expected widgetId to be defined";
                }

                if (this._ifWidgetAlreadyExists(widgetDefinition.widgetId)) {
                    this.element.find("li#" + widgetDefinition.widgetId).effect("shake", {
                        times : 3
                    }, 800);
                } else {
                    this.options.dashboardData.push(widgetDefinition);
                    var widget = this._constructWidget(widgetDefinition);
                    this.element.prepend(widget);
                    this._renderChart(widgetDefinition);
                }
            },
            //remove a widget from the dashboard
            removeWidget : function(widgetId) {
                if (!widgetId) {
                    throw "Expected widgetId to be defined";
                }
                var idSelector = "#" + widgetId;
                //get the dom element
                var widget = this.element.find("li" + idSelector);
                if (widget.length > 0) {
                    //delete the dom element
                    this.element.find("li" + idSelector).remove();
                    //remove the dom element from the widgetDefinition
                    this._removeWidgetFromWidgetDefinitions(widgetId);
                }
            },

            //get the wigetDefinitions
            getDashboardData : function() {
                return this.options.dashboardData;
            },
            destroy : function() {
                // call the base destroy function
                $.Widget.prototype.destroy.call(this);
            }
        });

    }));

