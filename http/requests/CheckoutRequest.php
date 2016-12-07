<?php namespace SublimeArts\SublimeStripe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SublimeArts\SublimeStripe\Models\Settings;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class CheckoutRequest extends FormRequest
{

    public function authorize()
    {

        return true;

    }

    public function rules()
    {

        return [
            'stripeEmail' => 'required|email',
            'stripeToken' => 'required',
            'product' => 'required'
        ];

    }

    public function submit()
    {
        Stripe::setApiKey(Settings::get('stripe_secret_key'));
     
        $productModel = '\\' . trim(Settings::get('target_model'));
        $product = $productModel::findOrFail($this->product['id']);
        
        $customer = Customer::create([
            'email' => $this->stripeEmail,
            'source' => $this->stripeToken
        ]);

        Charge::create([
            'customer' => $customer->id,
            'amount' => $product->{Settings::get('amount_attribute')},
            'currency' => 'usd'
        ]);

    }

}
