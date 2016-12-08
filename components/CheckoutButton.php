<?php namespace SublimeArts\SublimeStripe\Components;

use Cms\Classes\ComponentBase;
use SublimeArts\SublimeStripe\Models\Settings;;
use Log, Exception, Response;

class CheckoutButton extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Stripe Checkout Button',
            'description' => 'Provides a button to start the Stripe checkout process.'
        ];
    }

    public function defineProperties()
    {
        return [
            "buttonText" => [
                "title" => "Button Text",
                "description" => "Set the text for the checkout button.",
                "default" => "Checkout",
                "type" => "String"
            ],
            "productId" => [
                "title" => "Product ID",
                "description" => "Unique ID of the product meant to be associated with this button.",
                "type" => "String"
            ],
        ];
    }

    public function onRun()
    {
        Settings::checkRequired();

        $this->page['saStripeButtonText'] = $this->property('buttonText');
        $this->page['stripePostUri'] = Settings::get('post_uri');
        $this->page['stripeButtonClasses'] = Settings::get('button_classes');

    }

    /**
     * Returns the product object this button is associated with
     * @return object
     */
    public function nativeProduct()
    {
        $model = Settings::productModelClass();
        
        try {
            $product = $model::where('id', $this->property('productId'))->first();
        } catch (Exception $e) {
            throw new Exception("No product found", 1);    
        }

        return $product;
    }

    /**
     * Returns a json encoded array with the native product attribute values
     * mapped to attribute names needed by Stripe.
     * @return array
     */
    public function mappedProduct()
    {
        $product = $this->nativeProduct();
        
        $mappedProduct = [];
        $mappedProduct['id'] = $product->{Settings::get('id_attribute')};
        $mappedProduct['name'] = $product->{Settings::get('name_attribute')};
        $mappedProduct['description'] = $product->{Settings::get('description_attribute')};
        $mappedProduct['amount'] = $product->{Settings::get('amount_attribute')};

        return json_encode($mappedProduct);
    }

}
