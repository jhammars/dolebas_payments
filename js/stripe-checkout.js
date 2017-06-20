(function ($, Drupal) {
    Drupal.behaviors.wistiaUploadBehavior = {
        attach: function (context, settings) {

            var handler = StripeCheckout.configure({
                key: settings.stripe_publishable_key,
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                locale: 'auto',
                token: function(token) {

                    getCsrfToken(function ( csrToken ) {
                        PostNode(csrToken);
                    });

                    function PostNode(csrfToken) {
                        var body = {
                            "data": {
                                "type": "node--dolebas_transaction",
                                "attributes": {
                                    "title": "My test title",
                                    "uuid": drupalSettings.transaction_uuid,
                                    "field_stripe_token": token.id,
                                    "field_currency": {
                                        "value": drupalSettings.currency
                                    },
                                    "field_amount": {
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
                                var my_json = jQuery.ajax({
                                    url: "/jsonapi/node/dolebas_transaction/" + drupalSettings.transaction_uuid,
                                    method: 'GET',
                                    headers: {
                                        'Accept': 'application/vnd.api+json'
                                    },
                                    data: JSON.stringify(body),
                                    success: function (body) {
                                        console.log(body);
                                        document.getElementById("ajax-target").innerHTML = "Payment " + body.data.attributes.field_status;
                                    }
                                });
                            }
                        });

                    }

                    console.log(token.id);
                    // You can access the token ID with `token.id`.
                    // Get the token ID to your server-side code for use.
                }
            });

            // console.log("after handler");

            document.getElementById('customButton').addEventListener('click', function(e) {
                // Open Checkout with further options:
                handler.open({
                    name: 'Dolebas',
                    description: '2 widgets',
                    zipCode: true,
                    currency: 'sek',
                    amount: 2000
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