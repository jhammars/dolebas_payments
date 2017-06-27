(function ($, Drupal) {
    Drupal.behaviors.wistiaUploadBehavior = {
        attach: function (context, settings) {

            var handler = StripeCheckout.configure({
                key: settings.stripe_publishable_key,
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                locale: 'auto',
                token: function(token) {

                    getCsrfToken(function ( csrToken ) {
                        postNode(csrToken);
                    });

                    function postNode(csrfToken) {
                        var body = {
                            "data": {
                                "type": "node--dolebas_transaction",
                                "attributes": {
                                    "title": "Dolebas Transaction",
                                    "uuid": drupalSettings.transaction_uuid,
                                    "field_dolebas_trans_charge_token": token.id,
                                    "field_dolebas_trans_parent_ref": drupalSettings.parent_nid,
                                    "field_dolebas_trans_currency": drupalSettings.currency,
                                    "field_dolebas_trans_type": drupalSettings.transaction_type,
                                    "field_dolebas_trans_processor": drupalSettings.processor,
                                    "field_dolebas_trans_amount": {
                                        "value": drupalSettings.amount,
                                        "format": "number"
                                    }
                                }
                            }
                        };

                        jQuery.ajax({
                            url: '/jsonapi/node/dolebas_transaction?_format=json&token=' + csrfToken,
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/vnd.api+json',
                                'Accept': 'application/vnd.api+json'
                            },
                            data: JSON.stringify(body),
                            success: function (body) {
                                console.log(body);
                                jQuery.ajax({
                                    url: "/jsonapi/node/dolebas_transaction/" + drupalSettings.transaction_uuid,
                                    method: 'GET',
                                    headers: {
                                        'Accept': 'application/vnd.api+json'
                                    },
                                    data: JSON.stringify(body),
                                    success: function (body) {
                                        console.log(body);
                                        // Remove submit payment button from DOM after receiving positive payment confirmation
                                        document.getElementById( "customButton" ).remove();
                                        // Insert payment confirmation message into DOM
                                        document.getElementById( "ajax-target" ).innerHTML = "Payment " + body.data.attributes.field_dolebas_trans_status;
                                    }
                                });
                            }
                        });

                    }
                    console.log(token.id);
                }
            });

            document.getElementById('customButton').addEventListener('click', function(e) {
                // Open Checkout with further options:
                handler.open({
                    name: 'Dolebas',
                    description: 'Custom text2',
                    zipCode: false,
                    currency: drupalSettings.currency,
                    amount: drupalSettings.amount
                });
                e.preventDefault();
            });

            // Close Checkout on page navigation:
            window.addEventListener('popstate', function() {
                handler.close();
            });

        }
    };
})(jQuery, Drupal);

function getCsrfToken(callback) {
    jQuery
        .get(Drupal.url('session/token'))
        .done(function (data) {
            var csrfToken = data;
            callback(csrfToken);
        });
}