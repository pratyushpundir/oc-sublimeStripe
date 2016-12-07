<?php namespace SublimeArts\SublimeStripe;

use Backend;
use System\Classes\PluginBase;
use SublimeArts\SublimeStripe\Models\Settings;

/**
 * SublimeStripe Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Sublime Stripe',
            'description' => 'Provides easy payment handling with Stripe',
            'author'      => 'Sublime Arts',
            'homepage'    => 'http://www.sublimearts.me',
            'icon'        => 'icon-cc-stripe'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // if ($userModel = Settings::get('user_model') && $userModel != '') {
        //     $userModel::extend(function($model) {

        //         $model->hasMany['payments'] = [
        //             'SublimeArts\SublimeStripe\Models\Payment',
        //             'softDelete' => true
        //         ];

        //     });
        // }
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'SublimeArts\SublimeStripe\Components\CheckoutButton' => 'stripeCheckoutButton',
            'SublimeArts\SublimeStripe\Components\StripeJs' => 'stripeJS',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'sublimearts.sublimestripe.manage_settings' => [
                'tab' => 'Sublime Stripe',
                'label' => 'Manage plugin settings.'
            ],
            'sublimearts.sublimestripe.access_stripe_payments' => [
                'tab' => 'Sublime Stripe',
                'label' => 'Access Stripe payments.'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [];
        return [
            'sublimestripe' => [
                'label'       => 'Stripe Payments',
                'url'         => Backend::url('sublimearts/sublimestripe/payments'),
                'icon'        => 'icon-cc-stripe',
                'permissions' => ['sublimearts.sublimestripe.access_stripe_payments'],
                'order'       => 500,
            ],
        ];
    }

    /**
     * Register Backend Settings
     * @return array
     */
    public function registerSettings()
    {
        return [
            'sublimestripe' => [
                'label'       => 'Sublime Stripe',
                'description' => 'Manage settings related to Stripe integration.',
                'icon'        => 'icon-cc-stripe',
                'class'       => 'SublimeArts\SublimeStripe\Models\Settings',
                'order'       => 500,
                'keywords'    => 'sublimestripe stripe payment payments checkout',
                'permissions' => ['sublimearts.sublimestripe.manage_settings']
            ]
        ];
    }

}
