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
            $stats = [
                'inc',
                'dec'
            ];

            $t->increments('id');
            $t->string('room_id');
            $t->string('message_id');
            $t->integer('user_id')->index();
            $t->integer('user_target_id')->index();
            $t->enum('status', $stats)->default('inc');
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
