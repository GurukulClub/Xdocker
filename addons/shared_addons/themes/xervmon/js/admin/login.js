;
(function ($) {
    "use strict";
    /* global window, jQuery, SITE_URL, setTimeout */

    $(function () {
        // Add the close link to all boxes with the closable class
        $(".closable").append('<a href="#" class="close">x</a>');

        // Close the notifications when the close link is clicked
        $("a.close").click(function () {
            $(this).fadeTo(200, 0); // This is a hack so that the close link fades out in IE
            $(this).parent().fadeTo(200, 0);
            $(this).parent().slideUp(400);
            return false;
        });

        // Fade in the notifications
        $(".notification").fadeIn("slow");
    });


    $.validator.addMethod('validateEmail', function (email) {
        // http://stackoverflow.com/a/46181/11236
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }, 'Invalid Email');


    // Moved out of the method to allow reuse of markup across multiple clicks
    var $forgotPasswordForm;

    function getForgotPasswordFrom() {
        if ($forgotPasswordForm && $forgotPasswordForm.length > 0) {
            return $forgotPasswordForm;
        }
        var keyForm = '';
        keyForm += '<form class="form-inline" id="reset-pass" ';
        keyForm += '<div class="control-group"> <label class="control-label" for="title">Username<span style="font-size:17px; color:red;">* ' +
            '</span></label><div class="controls"> ' +
            ' <input style="width:60%" type="text" id="user_name" class="input-small" maxlength="100" placeholder="Username" required> ' +
            ' </div></br>';
        keyForm += '<div class="control-group"> <label class="control-label" for="title">Email<span style="font-size:17px; color:red;">* ' +
            '</span></label><div class="controls"> ' +
            ' <input style="width:60%" type="email" id="email" class="input-small" maxlength="100" placeholder="Email" required> ' +
            ' </div> ';
        // keyForm += '</br><div class="control-group"> <span class="pull-left"><button id="launchserver_save" class="btn btn-primary" onclick="javascript: resetPassword(); return false;">Reset Password</button></span> </div>';

        keyForm += '</form>';
        $forgotPasswordForm = $(keyForm);
        // A dummy wrapper to hold the markup until it is inserted
        $('<div class="hide" />').appendTo('body').append($forgotPasswordForm);
        return $forgotPasswordForm;
    }

    window.forgotPasswordModal = function () {
        $forgotPasswordForm = getForgotPasswordFrom();
        console.log($forgotPasswordForm);
        setTimeout(function () {
            var validate = $forgotPasswordForm.validate({
                rules: {
                    email: {
                        required: true,
                        validateEmail: true
                    },
                    user_name: {
                        required: true,
                    }
                }
            });
            console.log(validate);
        }, 100);
        window.show_confirm_modal('Forgot Password', $forgotPasswordForm, 'Reset Password', 'Cancel', function (yes) {
            if (yes) {
                var email = $('#email').val();
                var user_name = $('#user_name').val();
                if (email !== "" || user_name !== "") {
                    window.resetPassword();
                    // setTimeout(resetPassword, 100);
                    // window.location = SITE_URL + "admin/login";
                } else {
                    $.pnotify({
                        email: 'Username & Email :  Error',
                        text: "Registered Username/Email is required",
                        type: 'error',
                        shadow: false
                    });
                    return false;
                }
            }
        });
    };

    window.resetPassword = function () {
        var user_name = $('#user_name').val();
        var email = $('#email').val();
        if (user_name === "" || email === "") {
            $.pnotify({
                title: 'Reset Password :  Error',
                text: "Please Enter Username and Email to reset password",
                type: 'error',
                shadow: false
            });
            return false;
        }
        //if(ValidateIPaddress(ip))
        //{
        return $.post(SITE_URL + 'users/reset_pass_ajax', {
            user_name: user_name,
            email: email
        }).done(function (data1) {
            var obj = JSON.parse(data1);
            //alert(obj);
            if (obj.status == 'OK') {
                $.pnotify({
                    title: 'Reset Password :  Success',
                    text: obj.message,
                    type: 'success',
                    shadow: false
                });
            } else {
                $.pnotify({
                    title: 'Reset Password :  Error',
                    text: obj.message + " - Contact Support",
                    type: 'error',
                    shadow: false
                });
                window.forgotPasswordModal();
            }
        });
        //}

    };

})(jQuery);
