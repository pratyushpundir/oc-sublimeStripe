<?php namespace SublimeArts\SublimeStripe\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('sublimearts_sublimestripe_users', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->smallInteger('base_user_id')->unique()->index();
            $table->string('stripe_id')->nullable()->index();
            $table->boolean('stripe_active')->default(0);
            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sublimearts_sublimestripe_users');
    }
}
