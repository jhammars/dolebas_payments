//(function ($, Drupal) {
//    Drupal.behaviors.stripeBehavior = {
//        attach: function (context, settings) {
//
//        }
//    };
//})(jQuery, Drupal);

// Create a Stripe client
var stripe = Stripe(drupalSettings.stripe_publishable_key);
//console.log(drupalSettings.stripe_publishable_key);
// Create an instance of Elements
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
    base: {
        color: '#32325d',
        lineHeight: '24px',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
};

// Create an instance of the card Element
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Handle form submission
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
    event.preventDefault();

    stripe.createToken(card).then(function(result) {
        if (result.error) {
            // Inform the user if there was an error
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
        } else {
            // Send the token to your server
            console.log(result.token.id);
            getCsrfToken(function (csrfToken) {
                postNode(csrfToken, 'video', result.token.id);
            });

        }
    });
});

function postNode(csrfToken, node_type, stripe_token) {
    var body = {
        "data": {
            "type": "node--" + node_type,
            "attributes": {
                "title": "My test title",
                "field_stripe_token": stripe_token,
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
        url: 'http://localhost/videofilter/jsonapi/node/video?_format=json&token=' + csrfToken,
        method: 'POST',
        headers: {
            'Content-Type': 'application/vnd.api+json',
            'Accept': 'application/vnd.api+json'
        },
        data: JSON.stringify(body),
        success: function (body) {
            console.log(body);
        }
    });
}
