;toggleSidePanel = function() {
    // Show/Hide the sidepanel
    "use strict";
    var $sidePanel, $button, $sidePanelSpan, $mainContentSpan;

    $sidePanel = $("#cloud_accounts_holder_inner");
    $button = $("#cloud_accounts_hideButton");

    $sidePanelSpan = $("#cloud_accounts_holder_span");
    $mainContentSpan = $sidePanelSpan.siblings().first();

    if ($sidePanel.is(":visible")) {
        $button.find(".icon").removeClass("icon-arrow-left").addClass("icon-arrow-right");
        $sidePanel.hide();
        $sidePanelSpan.removeClass("span2").addClass("span0");
        $mainContentSpan.removeClass("span10").addClass("span12").css("margin-left", "0%");
    } else {
        $button.find(".icon").removeClass("icon-arrow-right").addClass("icon-arrow-left");
        $sidePanel.show();
        $sidePanelSpan.removeClass("span0").addClass("span2");
        $mainContentSpan.removeClass("span12").addClass("span10").css("margin-left", "2.564102564102564%");
    }
};