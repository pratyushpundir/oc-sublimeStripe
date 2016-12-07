<?php namespace SublimeArts\SublimeStripe\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete;

/**
 * Payment Model
 */
class Payment extends Model
{
    use SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'sublimearts_sublimestripe_payments';

    /**
     * @var array The date properties.
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'ip_address', 'amount_in_cents', 'stripe_invoice', 'charge_id'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        
    ];
    
}