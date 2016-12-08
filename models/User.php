<?php namespace SublimeArts\SublimeStripe\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete;
use SublimeArts\SublimeStripe\Traits\StripeBillable;

/**
 * User Model
 */
class User extends Model
{
    use StripeBillable, SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'sublimearts_sublimestripe_users';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'base_user_id',
        'stripe_id',
        'stripe_active'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [
        'subscription' => [
            'SublimeArts\SublimeStripe\Models\Subscription'
        ]
    ];

    public $hasMany = [
        'singleCharges' => [
            'SublimeArts\SublimeStripe\Models\SingleCharge'
        ]
    ];

    public $belongsTo = [
        'baseUser' => [
            'RainLab\User\Models\User',
            'key' => 'base_user_id'
        ]
    ];

}