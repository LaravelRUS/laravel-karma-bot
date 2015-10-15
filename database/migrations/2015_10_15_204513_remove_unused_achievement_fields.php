<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedAchievementFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achievements', function(Blueprint $t) {
            $t->dropColumn([
                'image',
                'title',
                'description'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('achievements', function(Blueprint $t) {
            $t->string('title');
            $t->string('description');
            $t->string('image');
        });
    }
}
