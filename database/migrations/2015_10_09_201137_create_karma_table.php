<?php

use App\Karma;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKarmaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karma', function(Blueprint $t){
            $t->uuid('id')->unique()->primary();
            $t->uuid('room_id')->index();
            $t->uuid('message_id')->index();
            $t->uuid('user_id')->index();
            $t->uuid('user_target_id')->index();
            $t->smallInteger('value')->default(1);
            $t->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('karma');
    }
}
