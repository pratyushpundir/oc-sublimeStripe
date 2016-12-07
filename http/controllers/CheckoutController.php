<?php namespace SublimeArts\SublimeStripe\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use SublimeArts\SublimeStripe\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller {

    public function checkout(CheckoutRequest $checkoutRequest)
    {
        try {
            $checkoutRequest->submit();
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 422);
        }

        return response()->json('All good! Thank you for your purchase!', 200);
    }

}
