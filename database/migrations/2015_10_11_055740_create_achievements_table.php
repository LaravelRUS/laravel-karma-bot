<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievements', function(Blueprint $t){
            $t->uuid('id')->unique()->primary();
            $t->string('name')->index();
            $t->integer('user_id')->index();
            $t->string('title');
            $t->string('description');
            $t->string('image');
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
        Schema::drop('achievements');
    }
}
