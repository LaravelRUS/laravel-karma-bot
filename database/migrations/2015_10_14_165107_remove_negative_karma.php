<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNegativeKarma extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('karma', function(Blueprint $t){
            $t->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('karma', function(Blueprint $t){
            $stats = [
                'inc',
                'dec'
            ];

            $t->enum('status', $stats)->default('inc');
        });
    }
}
