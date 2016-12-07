<?php namespace SublimeArts\SublimeStripe\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('sublimearts_sublimestripe_payments', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->smallInteger('user_id')->nullable();
            $table->smallInteger('ip_address')->nullable();
            $table->longText('charge_id')->nullable();
            $table->double('amount_in_cents')->nullable();
            $table->longText('stripe_invoice')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sublimearts_sublimestripe_payments');
    }
}
