<?php namespace SublimeArts\SublimeStripe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SublimeArts\SublimeStripe\Models\Settings;
use SublimeArts\SublimeStripe\Models\SingleCharge;
use SublimeArts\SublimeStripe\Models\Payment;
use RainLab\User\Facades\Auth;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Log;

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
     
        $productModel = Settings::productModelClass();
        $product = $productModel::findOrFail($this->product['id']);

        $loggedUser = Auth::getUser()->user;

        // Create a Single Charge
        $charge = SingleCharge::create([]);

        // Add the Payment
        $payment = Payment::create([
            'amount_in_cents' => $product->{Settings::get('amount_attribute')},
            'product_name' => $product->{Settings::get('name_attribute')}
        ]);

        $charge->payment = $payment;
        
        // Attach to user and product
        $loggedUser->singleCharges()->add($charge);
        $product->singleCharges()->add($charge);


        $productModelClass = Settings::productModelClass();
        if ( $product instanceof $productModelClass ) {
                $customer = Customer::create([
                'email' => $loggedUser->baseUser->email,
                'source' => $this->stripeToken
            ]);

            Charge::create([
                'customer' => $customer->id,
                'amount' => $product->{Settings::get('amount_attribute')},
                'currency' => 'usd'
            ]);

            $loggedUser->update([ 'stripe_id' => $customer->id]);
        } else {
            throw new Exception('$product needs to be an instance of ' . Settings::productModelClass());
        }

    }

}
