<?php namespace SublimeArts\SublimeStripe\Components;

use Cms\Classes\ComponentBase;
use SublimeArts\SublimeStripe\Models\Settings;

class StripeJs extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'StripeJs Component',
            'description' => 'Sets up required JavaScript variables. Add to Layout.'
        ];
    }

    /**
     * Passes the required data to a global (namespaced) JavaScript object so
     * that it's accessible by JS everywhere it is needed.
     */
    public function onRun()
    {
        Settings::checkRequired();

        if ( ! Settings::get('minify_assets')) {
            $this->addJs('/plugins/sublimearts/sublimestripe/assets/dist/js/bundle.js');
        } else {
            $this->addJs('/plugins/sublimearts/sublimestripe/assets/src/js/vendor/vue-2.1.3.js');
            $this->addJs('/plugins/sublimearts/sublimestripe/assets/src/js/sublimestripe-1.0.1.js');
        }

        $this->page['stripeKey'] = Settings::get('stripe_publishable_key');
        $this->page['stripePostUri'] = Settings::get('post_uri');
        $this->page['stripeRedirectUri'] = Settings::get('redirect_uri');
        $this->page['stripeContainerElement'] = Settings::get('container_html_element');
        // $this->page['stripeStoreImage'] = Settings::instance()->store_image->getPath();
        $this->page['stripeStoreImage'] = null;
    }

}