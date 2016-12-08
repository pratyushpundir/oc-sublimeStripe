<?php namespace SublimeArts\SublimeStripe\Models;

use Model;
use SublimeArts\SublimeStripe\Models\Settings;

/**
 * SingleCharge Model
 */
class SingleCharge extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'sublimearts_sublimestripe_single_charges';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    // protected $productModelClass = Settings::productModelClass();

    /**
     * @var array Relations
     */
    public $hasOne = [
        'payment' => [
            'SublimeArts\SublimeStripe\Models\Payment'
        ]
    ];
    public $belongsTo = [
        'user' => [
            'SublimeArts\SublimeStripe\Models\User'
        ],
        'product' => [
            'SublimeArts\DemoShop\Models\Product'
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}