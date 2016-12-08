<?php namespace SublimeArts\SublimeStripe\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('sublimearts_sublimestripe_subscriptions', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sublimearts_sublimestripe_subscriptions');
    }
}
