<?php namespace SublimeArts\SublimeStripe\Models;

use Model, Exception;

/**
 * Settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

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

    public function initSettingsData()
    {
        $this->minify_assets = false;
        $this->user_model = 'RainLab\User\Models\User';
        $this->id_attribute = 'id';
        $this->name_attribute = 'name';
        $this->description_attribute = 'description';
        $this->amount_attribute = 'amount';
        $this->post_uri = 'stripe/checkout';
        $this->redirect_uri = 'stripe/checkout/success';
        $this->button_classes = 'btn btn-primary';
        $this->backend_menu_item_title = 'Users & Payments';
    }

    public $settingsCode = 'sublimearts_sublime_stripe_settings';

    public $settingsFields = 'fields.yaml';

    protected $guarded = ['*'];

    public $attachOne = [
        'store_image' => 'System\Models\File'
    ];

    /**
     * Throws an Exception if the Settings defined in the REQUIRED const have not been set
     * or returns true if all is well
     * @return boolean
     */
    public static function checkRequired()
    {
        foreach (static::REQUIRED as $attribute => $attributeDescription) {
            if ( ! static::get($attribute) || static::get($attribute) == '' ) {
                throw new Exception("{$attributeDescription} not set in SublimeArts.SublimeStripe backend settings!", 1);
            } else {
                return true;
            }
        }
    }

    /**
     * Returns a fully-qualified and trimmed classname for the product Model Class
     * set in Backend Settings.
     * @return string
     */
    public static function productModelClass()
    {
        return '\\' . trim(static::get('product_model'));
    }

    /**
     * Returns a fully-qualified and trimmed classname for the user Model Class
     * set in Backend Settings.
     * @return string
     */
    public static function userModelClass()
    {
        return '\\' . trim(static::get('user_model'));
    }

    /**
     * Get a Product record by it's ID
     * @param  integer $productId  ID of the product to be fetched
     * @return Product             Instance of the Product model as set in Settings
     */
    public static function getProductById($productId)
    {
        $productModel = static::productModelClass();
        return $productModel::findOrFail($productId);
    }

}
