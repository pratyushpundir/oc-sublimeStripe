<?php


Route::post('stripe/checkout', 'SublimeArts\SublimeStripe\Http\Controllers\CheckoutController@checkout');
Route::post('stripe/webhooks', 'SublimeArts\SublimeStripe\Http\Controllers\WebhooksController@handle');