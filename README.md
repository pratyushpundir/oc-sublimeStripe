### Sublime Stripe - WORK IN PROGRESS! USE AT YOUR OWN RISK!
A simple plugin that provides a checkout button and simple backend integration with the the awesome [Stripe](https://stripe.com/) service for payment handling on any [OctoberCMS](https://octobercms.com/) site.

### Some Screenshots
![Checkout Buttons in a Product Listing](/screenshots/01-frontend-checkout-button.png?raw=true "Checkout Buttons in a Product Listing")
![Stripe Checkout Form](/screenshots/02-frontend-checkout-form-open.png?raw=true "Checkout Buttons in a Product Listing")
![Backend Settings - API Keys](/screenshots/03-backend-api-keys.png?raw=true "Checkout Buttons in a Product Listing")
![Backend Settings - Model Mapping](/screenshots/04-backend-model-mappings.png?raw=true "Backend Settings - Model Mapping")
![Backend Settings - Site Integration](/screenshots/05-backend-site-integration.png?raw=true "Backend Settings - Site Integration")
![Backend Settings - Styling](/screenshots/06-backend-styling.png?raw=true "Backend Settings - Styling")


### Setup part 1 - Stripe.com
1. Create a free account on [Stripe](https://stripe.com/).
2. Login to Stripe once you have confirmed your email address.
3. Visit https://dashboard.stripe.com/account/apikeys and make a copy of both your Secret and Publishable keys. Make sure to use the pair relevant to what you are doing, aka, testing or working on your live server.
4. Visit your own site's backend panel and keep reading.

### Setup part 2 - Your OctoberCMS website backend
1. Create a folder called "sublimearts" in your project's "plugins" directory.
2. Clone or download this repo inside the directory you created above. Make sure this new cloned/downloaded directory is named "sublimestripe". Your directory structure should look something like ```OctoberSite\plugins\sublimearts\sublimestripe```.
1. Login to the Backend and visit Settings. Look for Sublime Stripe under MISC OR just search for "Stripe" in the left sidebar on the Settings page.
2. Set your API keys in the fields provided. Move on to other tabs.
3. Visit every tab in Sublime Stripe Settings, pay attention to comments under each field and make sure you do not miss anything.

### Setup part 3 - Components
Sublime Stripe provides 2 components, both of which are required.

1. Include the "stripeJS" component and the ```{% scripts %}``` tag in any layout that needs to host a page where the checkout button might need to appear. Make sure the component is included BEFORE the ```{% scripts %}``` tag. Your setup should look something like this:

```twig
description = "Master layout"

[stripeJS]
==
<!DOCTYPE html>
<html>
    <head>
        <title>Demo site for SublimeStripe Plugin</title>
        <link rel="stylesheet" href="{{ 'assets/css/vendor.css'|theme }}">
        <link rel="stylesheet" href="{{ 'assets/css/theme.css'|theme }}">
    </head>
    <body>


        <!-- Content -->
        <section id="layout-content" class="container-fluid">
            {% page %}
        </section>


        <!-- Scripts -->
        <script src="{{ 'assets/vendor/jquery.js'|theme }}"></script>
        <script src="{{ 'assets/vendor/bootstrap.js'|theme }}"></script>
        
        {% component "stripeJS" %}
        {% framework extras %}
        {% scripts %}

    </body>
</html>
```
2. Hunt down the plugin/component that provides your product listing. In that component add "stripeCheckoutButton" component as a dependency by adding the following line in your component's ```init()``` method like so:
```php
public function init()
{
    $this->addComponent('SublimeArts\SublimeStripe\Components\CheckoutButton', 'checkoutButton', []);
}
```
3. Now when you need to display a button to kick off the Stripe Payment handling process, just include the "stripeCheckoutButton" component and pass your products' id to the ```productId``` component property. Example:
```twig
{% component "checkoutButton" productId=product.id %}
```


### TODO
- [ ] Too much to list here. This is still very much a work in progress. I will update this list in the coming days.
- [ ] Write tests!!!
- [ ] Figure out integration with User models. Either add a dependency on RainLab.User or allow for any custom User model to be used which might end up being tricky.
- [ ] Stripe webhooks integration.


### Developer Info
[SublimeArts](https://www.sublimearts.me)