(function ($) {
    'use strict';
    $(".form-login form").validate({
        errorElement: "div",
        rules: {
            password: {
                required: true,
                minlength: 6
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            password: {
                required: "Please provide a password !",
                minlength: "Your password must be at least 6 characters long !"
            },
            email: "Please enter a valid email address"
        }
    });

})(jQuery)