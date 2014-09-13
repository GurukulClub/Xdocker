; // JavaScript Document
/* global $, document, setInterval */

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
        $('#xervmon-mainContent,.xervmon-mainContent').animate({
            marginLeft: 42,
            display: 'toggle'
        }, 'slow');
        $(this).addClass('collapseBtn');
        $("#accordion2  .nav-header, .accordion2  .nav-header").css("color", "#126DA3");
        $("#accordion2 .accordion-group, .accordion2 .accordion-group").addClass('mouseOverEffect');
    }, function hide() {
        $(this).removeClass('collapseBtn');
        $("#accordion2 .accordion-group, .accordion2 .accordion-group").removeClass('mouseOverEffect');
        $('#xervmon-mainContent, .xervmon-mainContent').animate({
            marginLeft: 190,
            display: 'toggle'
        }, 'slow');
        $("#accordion2 .nav-header, .accordion2 .nav-header").css("color", "#9DC3D9");
    });

    $(".accordion-heading").click(function () {
        $('.accordion .accordion-group').removeClass('selectedAccordian');
        $(this).parent().addClass('selectedAccordian');
        if ($(this).parent().find('.accordion-body').hasClass('in')) {
            $(this).parent().removeClass('selectedAccordian');
        }
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
        $("tr#highPriority,tr.highPriority").each(function () {
            $(this).find('td').toggleClass("bgColor");
        })
    }


    $(".selectedAccordian ").each(function () {
        var getIdN = $(this).attr("id");
        var contentIdN = "xervmon-" + getIdN + "Content";
        $("#" + contentIdN).show();
    })
    $("#accordion2 .accordion-group, .accordion2 .accordion-group").click(function () { //alert("hi")
        $(".rightContent").hide();
        var getId = $(this).attr("id");
        var contentId = "xervmon-" + getId + "Content";
        $("#" + contentId).show();
        var ht = $(this).height();
        if (ht > 38) {
            $("#dashboard, .dashboard").addClass('selectedAccordian');
            $("#" + contentId).hide();
            $("#xervmon-dashboardContent, .xervmon-dashboardContent").show();
        }

    });

});

$(function () {
    "use strict";

    // Hack to migrate to flat
    // $('.xervmon-inputCheckbox').removeClass('xervmon-inputCheckbox').addClass('checkbox');

    $(".radio input[type=radio]:not(.skip)").each(function () {
        $(this).radio();
    }).livequery(function () {
        $(this).radio();
    });
    $(".radio").livequery('show mouseenter hover', function () {
        $(this).find('input[type=radio]:not(.skip)').radio();
    });


    $(".checkbox input[type=checkbox]:not(.skip)").each(function () {
        $(this).checkbox();
    }).livequery(function () {
        $(this).checkbox();
    });
    $(".checkbox").livequery('show mouseenter hover', function () {
        $(this).find('input[type=checkbox]:not(.skip)').checkbox();
    });

    var setColorlorToValue = function () {
        var $this = $(this);
        $this.css('background', $this.val());
    };
    $('.js-colorpicker').livequery(function () {
        try {
            $(this).colorpicker().on('create changeColor showPicker change', setColorlorToValue);
        } catch (err) {
            console.warn(err.message, err.stack);
        }
    }).livequery('change mouseenter show', setColorlorToValue);
});
