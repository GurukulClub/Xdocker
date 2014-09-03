; // JavaScript Document

/* global $, document, window */

$(document).mouseup(function (e) {
    var container = $(".settingsDetail");
    var settingsClick = $(".settings");
    var settingsClicNk = $(".settingsHide");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0 && !settingsClick.is(e.target)) // ... nor a descendant of the container
    {
        { //alert($(".settingsDetail").has(e.target).length)
            $('.settings').removeClass('settingsHide')
            container.animate({
                top: -150,
            }, 'slow').hide(1000);
        }
    }
    if (settingsClick.is(e.target)) {
        {
            $('.settings').addClass('settingsHide')
            container.animate({
                top: 28,
            }, 'slow').show();
        }
    }
    if (settingsClicNk.is(e.target)) {
        {
            $('.settings').removeClass('settingsHide')
            container.animate({
                top: -150,
            }, 'slow').hide(1000);
        }
    }
});
$(document).ready(function () {
    $(".expdBtn").toggle(function show() {
        $('#xervmon-mainContent').animate({
            marginLeft: 42,
            display: 'toggle'
        }, 'slow');
        $(this).addClass('collapseBtn');
        $("#accordion2  .nav-header").css("color", "#126DA3");
        $("#accordion2 .accordion-group").addClass('mouseOverEffect');
        $(window).trigger("resize");
    }, function hide() {
        $(this).removeClass('collapseBtn');
        $("#accordion2 .accordion-group").removeClass('mouseOverEffect');
        $('#xervmon-mainContent').animate({
            marginLeft: 190,
            display: 'toggle'
        }, 'slow');
        $("#accordion2 .nav-header").css("color", "#9DC3D9");
        $(window).trigger("resize");
    });

    $(".mouseOverEffect").live("mouseover", function () {
        $(this).css({
            'position': 'relative',
            'z-index': '10'
        });
        $(this).addClass("selectedAccordian");
        //$(this).find('.accordion-body').addClass('in');
    });
    $(".mouseOverEffect").live("mouseout", function () {
        $(this).css({
            'position': 'none',
            'z-index': '1'
        });
        $(this).removeClass("selectedAccordian");
        //$(this).find('.accordion-body').removeClass('in');
    });
    $(".xervmon-search input").focus(function () {
        $(this).animate({
            display: 'toggle',
            width: '265px'
        }, 'slow');
    });
    $(".xervmon-search input").focusout(function () {
        $(this).animate({
            display: 'toggle',
            width: '206px'
        }, 'slow');
    });

    setInterval(blinkText, 1000);

    function blinkText() {
        $(".blink").each(function () {
            $(this).toggleClass("txtColor");
        })
    }

    setInterval(findYellow, 1000);

    function findYellow() {
        $("tr#highPriority").each(function () {
            $(this).find('td').toggleClass("bgColor");
        })
    }


    $(".selectedAccordian ").each(function () {
        var getIdN = $(this).attr("id");
        var contentIdN = "xervmon-" + getIdN + "Content";
        $("#" + contentIdN).show();
    });

    $("#accordion2 .accordion-heading").click(function () {
        $('.accordion .accordion-group').removeClass('selectedAccordian');
        $(this).parent().addClass('selectedAccordian');
        var accdBdyHt = $(this).parent().find('.accordion-body').height();

        $(".rightContent").hide();
        var getId = $(this).parent().attr("id");
        var contentId = "xervmon-" + getId + "Content";
        $("#" + contentId).show();
        if ($(this).parent().find('.accordion-body').hasClass('in') && accdBdyHt > 20) {
            $("#" + contentId).hide();
            $(this).parent().removeClass('selectedAccordian');
            $("#dashboard").addClass('selectedAccordian');
            $("#xervmon-dashboardContent").show();
        }

    });

    $(".xervmon-inputCheckbox").click(function () {
        if ($(this).find('input.checkbox').is(':checked')) {

            $('.rackspaceHiddenConetnt').slideDown();
        } else {
            $('.rackspaceHiddenConetnt').slideUp();
        }
    });

    $("#accordion3 .accordion-group .accordion-heading").click(function () {
        $('.accordion-heading').find('a').removeClass('selected');
        $(this).find('a').addClass('selected');
    });

    var el = $('#inputVal');

    function change(amt) {
        el.val(parseInt(el.val(), 10) + amt);
    }


    $('.upArrow').click(function () {
        change(1);
    });
    $('.downArrow').click(function () {
        change(-1);
    });

    $("ul.subTabs > li > a").click(function () {
        $("ul.subTabs > li").removeClass('active');
        $("#rackspaceContent").hide();
        $(".subTabsContent").hide();
        var getId = $(this).attr("rel");
        $(this).parent().addClass('active');
        $("#" + getId).show();
    })
    $(".rackspaceTab").click(function () {
        $("#rackspaceContent").show();
        $(".subTabsContent").hide();
        $("ul.subTabs > li").removeClass('active');
    })

    $(".iconEye").click(function () {
        if ($(this).hasClass('enabled')) {
            $(this).removeClass('enabled');
            $(this).attr("title", "Click to Enable")
        } else {
            $(this).addClass('enabled');
            $(this).attr("title", "Click to Disable")
        }

    });
    $(".addOns .iconClose").click(function () {
        if ($(this).hasClass('iconInstall')) {
            $(this).removeClass('iconInstall');
            $(this).attr("title", "Click to Uninstall")
        } else {
            $(this).addClass('iconInstall');
            $(this).attr("title", "Click to Install");
        }
    });

});

$(function () {
    "use strict";

    // Hack to migrate to flat
    // $('.xervmon-inputCheckbox').removeClass('xervmon-inputCheckbox').addClass('checkbox');

    $(".radio input[type=radio]:not(.skip)").each(function () {
        $(this).radio();
    }).livequery('create update load', function () {
        $(this).radio();
    });
    $(".radio").livequery('mouseenter hover', function () {
        $(this).find('input[type=radio]:not(.skip)').radio();
    });


    $(".checkbox input[type=checkbox]:not(.skip)").each(function () {
        $(this).checkbox();
    }).livequery('create update load', function () {
        $(this).checkbox();
    });
    $(".checkbox").livequery('mouseenter hover', function () {
        $(this).find('input[type=checkbox]:not(.skip)').checkbox();
    });
});
