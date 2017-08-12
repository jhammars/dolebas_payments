(function ($, Drupal, document) {
    Drupal.behaviors.stripeCheckoutBehavior = {
        attach: function (context, settings) {
            var handler = StripeCheckout.configure({
                key: settings.stripe_publishable_key,
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                locale: 'auto',
                token: function(token) {
                    document.getElementById("customButton").remove();
                    // Insert payment confirmation message into DOM
                    document.getElementById( "ajax-target" ).innerHTML = "Processing Payment....";

                    getCsrfToken(function ( csrToken ) {
                        postNode(csrToken);
                    });

                    function postNode(csrfToken) {
                        var body = {
                            "data": {
                                "type": "node--dolebas_transaction",
                                "attributes": {
                                    "title": drupalSettings.transaction_uuid,
                                    "uuid": drupalSettings.transaction_uuid,
                                    "field_dolebas_trans_charge_token": token.id,
                                    "field_dolebas_trans_parent_ref": drupalSettings.parent_nid,
                                    "field_dolebas_trans_type": drupalSettings.transaction_type,
                                    "field_dolebas_trans_processor": drupalSettings.processor
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
                    description: '1 month video storage',
                    zipCode: false,
                    currency: drupalSettings.currency_for_display,
                    amount: drupalSettings.amount_for_display
                });
                e.preventDefault();
            });

            // Close Checkout on page navigation:
            window.addEventListener('popstate', function() {
                handler.close();
            });

        }
    };
})(jQuery, Drupal, document);

function getCsrfToken(callback) {
    jQuery
        .get(Drupal.url('session/token'))
        .done(function (csrfToken) {
            callback(csrfToken);
        });
}