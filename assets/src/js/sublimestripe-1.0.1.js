var SublimeStripeApp = new Vue({
    el: window.SublimeStripeData.containerElement,

    data: {
        stripeEmail: '',
        stripeToken: '',
        product: {}
    },

    methods: {
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

        this.stripe = StripeCheckout.configure({
            key: window.SublimeStripeData.key,
            image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
            // email: if (window.SublimeStripeData.loggedEmail) 
            //     ? window.SublimeStripeData.loggedEmail 
            //     : null,
            locale: "auto",
            token: function (token) {
                this.stripeEmail = token.email;
                this.stripeToken = token.id;

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
                checkout: function() {
                    this.$emit('checkout', this.product);
                }
            }

        }
    }
});