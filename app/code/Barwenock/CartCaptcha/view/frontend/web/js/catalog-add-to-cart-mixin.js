/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package CartCaptcha for Magento 2
 */
define(
    [
        'jquery',
        'mage/translate',
        'jquery/ui'
    ],
    function ($, $t) {
        'use strict';

        return function (target) {
            $.widget('mage.catalogAddToCart', target, {
                /**
                 * Handler for the form 'submit' event
                 *
                 * @param {jQuery} form
                 */
                submitForm: function (form) {
                    this.addCaptchaValidation().then((captchaResult) => {
                        if (captchaResult) {
                            this.ajaxSubmit(form);
                        }
                    });
                },

                /**
                 * V3 Captcha validator
                 */
                addCaptchaValidation: function () {
                    return new Promise((resolve) => {
                        let recaptchaScript = document.createElement('script');
                        let googleApiKey = 'YOUR_RECAPTCHA_SITE_KEY';
                        let captchaResult = true;
                        recaptchaScript.src = 'https://www.google.com/recaptcha/api.js?render=' + googleApiKey;
                        document.head.appendChild(recaptchaScript);

                        recaptchaScript.onload = function () {
                            grecaptcha.ready(function () {
                                grecaptcha.execute(googleApiKey, { action: 'submit' }).then(function (token) {
                                    $.ajax({
                                        url: window.location.origin + '/cartCaptcha/captcha/validation',
                                        type: 'POST',
                                        headers: {
                                            'x-recaptcha': token
                                        },
                                        success: function (response) {
                                            captchaResult = true;
                                            resolve(captchaResult); // Resolve the Promise here
                                        },
                                        error: function (xhr, status, error) {
                                            captchaResult = false;
                                            resolve(captchaResult); // Resolve the Promise here
                                        }
                                    });
                                });
                            });
                        };
                    });
                }
            });

            return $.mage.catalogAddToCart;
        };
    }
);
