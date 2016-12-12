### Sublime Stripe [![Build Status](https://travis-ci.org/pratyushpundir/oc-sublimeStripe.svg?branch=master)](https://travis-ci.org/pratyushpundir/oc-sublimeStripe)
A simple plugin that provides a checkout button and simple backend integration with the the awesome [Stripe](https://stripe.com/) service for payment handling on any [OctoberCMS](https://octobercms.com/) site.

### Some Screenshots
![Checkout Buttons in a Product Listing](/assets/images/01-frontend-checkout-button.png?raw=true "Checkout Buttons in a Product Listing")
![Stripe Checkout Form](/assets/images/02-frontend-checkout-form-open.png?raw=true "Stripe Checkout Form")
![Backend Page - Users](/assets/images/03-backend-page-users.png?raw=true "The modified Users page with Stripe related columns")
![Backend Page - Single Charges](/assets/images/04-backend-page-single-charges.png?raw=true "Stripe individual payments")
![Backend Settings - API Keys](/assets/images/05-backend-settings-api-keys.png?raw=true "Backend Settings - Stripe API Keys")
![Backend Settings - Model Mapping](/assets/images/06-backend-settings-model-mappings.png?raw=true "Backend Settings - Model Mapping")
![Backend Settings - Site Integration](/assets/images/07-backend-settings-site-integration.png?raw=true "Backend Settings - Site Integration")
![Backend Settings - Styling](/assets/images/08-backend-settings-styling.png?raw=true "Backend Settings - Styling")


### Setup part 1 - Stripe.com
1. Create a free account on [Stripe](https://stripe.com/).
2. Login to Stripe once you have confirmed your email address.
3. Visit https://dashboard.stripe.com/account/apikeys and make a copy of both your Secret and Publishable keys. Make sure to use the pair relevant to what you are doing, aka, testing or working on your live server.
4. Visit your own site's backend panel and keep reading.

### Setup part 2 - Your OctoberCMS website backend
1. For now, this plugin has a dependency on the [RainLab.User](https://github.com/rainlab/user-plugin) plugin. Hopefully we will be able to change that in the near future.
2. Create a folder called "sublimearts" in your project's "plugins" directory.
3. Clone or download this repo inside the directory you created above. Make sure this new cloned/downloaded directory is renamed to "sublimestripe". Your directory structure should look something like ```OctoberSite\plugins\sublimearts\sublimestripe```.
4. Login to the Backend and visit Settings. Look for Sublime Stripe under MISC or just search for "Stripe" in the left sidebar on the Settings page.
5. Set your API keys in the fields provided. Move on to other tabs.
6. Visit every tab in Sublime Stripe Settings, pay attention to comments under each field and make sure you do not miss anything.
7. Visit your project root in the terminal and run ```php artisan plugin:refresh SublimeArts.SublimeStripe```.

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
- [ ] Too much to list here. This is still very much a work in progress. I will keep updating this list in the coming days.
- [ ] Write tests!!!
- [ ] Clean up CheckoutRequest. Very hacky right now.
- [ ] Account for Subscriptions.
- [ ] Setup more useful backend pages.
- [ ] Figure out integration with User models. Either add a dependency on RainLab.User or allow for any custom User model to be used which might end up being tricky.
- [ ] Stripe webhooks integration.
- [ ] Add transactional email and some mail templates.


### Developer Info
[SublimeArts](https://www.sublimearts.me)