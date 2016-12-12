<?php namespace SublimeArts\SublimeStripe\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSingleChargesTable extends Migration
{
    public function up()
    {
        Schema::create('sublimearts_sublimestripe_single_charges', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            
            $table->smallInteger('user_id')->unsigned()->nullable();
            $table->smallInteger('subscription_id')->unsigned()->nullable();
            $table->smallInteger('product_id')->unsigned()->nullable();
            $table->string('ip_address')->nullable();
            $table->double('amount_in_cents')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_invoice')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sublimearts_sublimestripe_single_charges');
    }
}
