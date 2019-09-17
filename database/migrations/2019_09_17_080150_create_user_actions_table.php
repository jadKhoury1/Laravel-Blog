<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->enum('action', ['ADD', 'EDIT', 'DELETE']);
            $table->integer('item_id')->unsigned();
            $table->string('item_type');
            $table->text('data')->nullable();
            $table->integer('status')->default(0);
            $table->bigInteger('action_taken_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');

            $table->foreign('action_taken_by')
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL')
                ->onUpdate('SET NULL');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_actions');
    }
}
