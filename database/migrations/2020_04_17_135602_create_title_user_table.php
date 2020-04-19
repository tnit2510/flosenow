<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTitleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('title_user', function (Blueprint $table) {
            $table->foreignId('title_id');
            $table->foreignId('user_id');
            $table->string('receive_at');

            $table->foreign('title_id')->references('id')
                ->on('titles')
                ->onDelete('cascade');

            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('title_user');
    }
}
