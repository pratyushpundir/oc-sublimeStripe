<?php namespace SublimeArts\SublimeStripe\Models;

use Model, Exception;

/**
 * Settings Model
 */
class Settings extends Model
{
    /**
     * Settings that are REQUIRED to be set before using the plugin
     */
    const REQUIRED = [
        "stripe_publishable_key" => "'Stripe Publishable Key'",
        "stripe_secret_key" => "'Stripe Secret Key'",
        "product_model" => "'Product Model'",
        "id_attribute" => "'ID attribute'",
        "name_attribute" => "'Name attribute'",
        "description_attribute" => "'Description attribute'",
        "amount_attribute" => "'Amount attribute'",
        "container_html_element" => "'Container element'",
        "post_uri" => "'POST URI'",
        "redirect_uri" => "'Redirect URI'",
        "button_classes" => "'Button Classes'",
    ];

    public $implement = ['System.Behaviors.SettingsModel'];

    public function initSettingsData()
    {
        $this->test_mode = true;
        $this->user_model = 'RainLab\User\Models\User';
        $this->id_attribute = 'id';
        $this->name_attribute = 'name';
        $this->description_attribute = 'description';
        $this->amount_attribute = 'amount';
        $this->post_uri = 'stripe/checkout';
        $this->redirect_uri = 'stripe/checkout/success';
        $this->button_classes = 'btn btn-primary';
    }

    public $settingsCode = 'sublimearts_sublime_stripe_settings';

    public $settingsFields = 'fields.yaml';

    protected $guarded = ['*'];

    public $attachOne = [
        'store_image' => 'System\Models\File'
    ];

    /**
     * Checks if the Settings defined in the REQUIRED const have been set.
     * @return 
     */
    public static function checkRequired()
    {
        foreach (static::REQUIRED as $attribute => $attributeDescription) {
            if ( ! static::get($attribute) ) {
                throw new Exception("{$attributeDescription} not set in SublimeStripe backend settings!", 1);
                // return Response::make("{$attributeDescription} not set in SublimeStripe backend settings!", 500);
            }
        }
    }

    /**
     * Returns a fully-qualified and trimmed classname for the target Model Class.
     * @return string
     */
    public static function productModelClass()
    {
        return '\\' . trim(static::get('product_model'));
    }

    /**
     * Returns a fully-qualified and trimmed classname for the User Class.
     * @return string
     */
    public static function userModelClass()
    {
        return '\\' . trim(static::get('user_model'));
    }

}
