<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarkPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmark_post', function (Blueprint $table) {
            $table->foreignId('bookmark_id');
            $table->foreignId('post_id');

            $table->foreign('bookmark_id')->references('id')
                ->on('bookmarks')
                ->onDelete('cascade');

            $table->foreign('post_id')->references('id')
                ->on('posts')
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
        Schema::dropIfExists('bookmark_post');
    }
}
