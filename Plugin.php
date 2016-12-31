<?php namespace SublimeArts\SublimeStripe;

use Backend, Event;
use System\Classes\PluginBase;
use SublimeArts\SublimeStripe\Models\Settings;
use SublimeArts\SublimeStripe\Models\User;
use Stripe\Stripe;
use RainLab\User\Models\User as BaseUser;

/**
 * SublimeStripe Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * @var array Plugin dependencies
     */
    public $require = ['RainLab.User'];

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
        /** Check that required settings have been set */
        Settings::checkRequired();

        /** Set the Stripe API Key */
        Stripe::setApiKey(Settings::get('stripe_secret_key'));

        /** Extend the RainLab User Model class */
        BaseUser::extend(function($model) {

            $model->hasOne['user'] = [
                'SublimeArts\SublimeStripe\Models\User',
                'key' => 'base_user_id',
                'softDelete' => true
            ];

            $model->bindEvent('model.afterCreate', function() use ($model) {
                if ( ! User::where('base_user_id', $model->id)->first() ) {
                    User::create([
                        'base_user_id' => $model->id
                    ]);
                }
            });

            $model->bindEvent('model.afterDelete', function() use ($model) {
                if ( $user = User::where('base_user_id', $model->id)->first() && ! $model->isSoftDelete()) {
                    $model->user()->forceDelete();
                }
            });

        });

        /** Extend the Product Model class */
        $productModelClass = Settings::productModelClass();
        $productModelClass::extend(function($model) {

            $model->hasMany['singleCharges'] = [
                'SublimeArts\SublimeStripe\Models\SingleCharge',
                'softDelete' => true
            ];
            
            $model->hasMany['subscriptions'] = [
                'SublimeArts\SublimeStripe\Models\Subscription',
                'softDelete' => true
            ];

        });

        /** Add some extra columns */
        Event::listen('backend.list.extendColumns', function($widget) {

            // Only for the User controller
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) {
                return;
            }

            // Only for the User model
            if (!$widget->model instanceof \RainLab\User\Models\User) {
                return;
            }

            /** Add extra columns to the User backend list */
            $widget->addColumns([
                'stripe_id' => [
                    'label' => 'Stripe ID',
                    'type' => 'partial',
                    'path' => '$/sublimearts/sublimestripe/models/user/_stripe_id.htm',
                    'clickable' => false
                ],
                'activated' => [
                    'label' => 'Account Active?',
                    'type' => 'partial',
                    'path' => '$/sublimearts/sublimestripe/models/user/_activated.htm',
                    'clickable' => false
                ],
                'stripe_active' => [
                    'label' => 'Stripe Active?',
                    'type' => 'partial',
                    'path' => '$/sublimearts/sublimestripe/models/user/_stripe_active.htm',
                    'clickable' => false
                ]
            ]);
        });
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
            'sublimearts.sublimestripe.manage_sublime_stripe' => [
                'tab' => 'Sublime Stripe',
                'label' => 'Manage Sublime Stripe.'
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
        return [
            'sublimestripe' => [
                'label' => (Settings::get('backend_menu_item_title'))
                            ? Settings::get('backend_menu_item_title')
                            : 'Stripe Payments',
                'url'         => Backend::url('sublimearts/sublimestripe/singlecharges'),
                'icon'        => 'icon-cc-stripe',
                'iconSvg'     => 'plugins/sublimearts/sublimestripe/assets/images/stripe-icon.svg',
                'permissions' => ['sublimearts.sublimestripe.manage_sublime_stripe'],
                'order'       => 500,

                'sideMenu' => [
                    'singlecharges' => [
                        'label' => 'Single Charges',
                        'icon' => 'icon-money',
                        'url' => Backend::url('sublimearts/sublimestripe/singlecharges'),
                        'permissions' => ['sublimearts.sublimestripe.*'],
                    ],
                    'subscriptions' => [
                        'label' => 'Subscriptions',
                        'icon' => 'icon-repeat',
                        'url' => Backend::url('sublimearts/sublimestripe/subscriptions'),
                        'permissions' => ['sublimearts.sublimestripe.*'],
                    ],
                ]
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
