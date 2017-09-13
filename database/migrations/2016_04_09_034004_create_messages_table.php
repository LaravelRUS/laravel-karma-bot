<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMessagesTable
 */
class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function(Blueprint $t) {
            $t->uuid('id')->unique()->primary();
            $t->string('gitter_id')->index();
            $t->uuid('room_id')->index();
            $t->uuid('user_id')->index();
            $t->longText('text');
            $t->longText('text_rendered');
            $t->timestamps();
        });
        
        Schema::create('urls', function(Blueprint $t) {
            $t->uuid('id')->unique()->primary();
            $t->uuid('message_id')->index();
            $t->string('url');
        });
        
        Schema::create('mentions', function(Blueprint $t) {
            $t->uuid('id')->unique()->primary();
            $t->uuid('message_id')->index();
            $t->uuid('user_id')->index();
        });

        Schema::create('message_relations', function(Blueprint $t) {
            $t->uuid('message_id')->index();
            $t->uuid('answer_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('urls');
        Schema::drop('messages');
        Schema::drop('mentions');
        Schema::drop('message_relations');
    }
}
