
/**
* SublimeStripe.js
* Super easy Stripe based payment handling for OctoberCMS based sites.
* 
* Version - 1.0.1
* Last Updated - December 16, 2016 - 12:19 PM
* License - MIT
* Author - Pratyush Pundir, Sublime Arts - https://www.sublimearts.me
*/

// ---------------------------------- x ----------------------------------

/** Look for the container element as set in plugin Backend Settings */
$sublimeStripeAppExists = $(window.SublimeStripeData.containerElement).length;

/** Do VueJS stuff only if the container element is found. */
if ($sublimeStripeAppExists) {
    var SublimeStripeApp = new Vue({
        el: window.SublimeStripeData.containerElement,

        data: {
            stripeEmail: '',
            stripeToken: '',
            product: {}
        },

        methods: {
            /**
             * Open the Stripe Checkout Form and set the product object.
             * @param  object product Product that relates to the clicked button
             */
            openCheckoutForm: function(product) {
                this.product = product;
                
                this.stripe.open({
                    name: product.name,
                    description: product.description,
                    zipCode: true,
                    amount: product.amount
                });
            }
        },

        created: function () {
            /**
             * Do some config as soon as Vue instance is created.
             */
            this.stripe = StripeCheckout.configure({
                
                key: window.SublimeStripeData.key,
                
                /** TODO: Use the actual image provided thru Backend Settings */
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                
                /** Use a logged user's email if available */
                email: (window.SublimeStripeData.loggedEmail) 
                    ? window.SublimeStripeData.loggedEmail 
                    : null,
                
                locale: "auto",
                
                token: function (token) {
                    this.stripeEmail = token.email;
                    this.stripeToken = token.id;

                    /** Make the required AJAX call to the server and send required data */
                    $.ajax({
                        type: "POST",
                        url: window.SublimeStripeData.postUri,
                        data: this.$data,
                        success: function (response) {
                            console.log(response);
                            // document.location.replace(window.SublimeStripeData.redirectUri);
                        }
                    });
                }.bind(this)
            });
        },

        components: {
            'sa-stripe-checkout-button': {

                template: '#sa-stripe-checkout-button-template',

                props: ['product'],

                methods: {
                    /**
                     * Emit an event on button click for the parent Vue instance to respond to.
                     */
                    checkout: function() {
                        this.$emit('checkout', this.product);
                    }
                }

            }
        }
    });
}