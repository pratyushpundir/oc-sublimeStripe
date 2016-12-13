<?php namespace SublimeArts\SublimeStripe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SublimeArts\SublimeStripe\Models\Settings;
use RainLab\User\Facades\Auth;
use Stripe\Stripe;

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

    /**
     * Handle the checkout data and create needed charges
     * @return
     */
    public function submit()
    {
        Stripe::setApiKey(Settings::get('stripe_secret_key'));

        $loggedUser = Auth::getUser()->user;

        $loggedUser->addSingleCharge($this->product['id'], $this->stripeToken);
    }

}
