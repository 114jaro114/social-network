<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('post_id');
            $table->integer('user_id');
            $table->string('text');
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();

            $table->string('status')->default('created');
            $table->string('chip')->nullable();

            $table->integer('count_comments')->default(0);
            $table->integer('count_reactions')->default(0);

            $table->integer('like')->default(0);
            $table->integer('heart')->default(0);
            $table->integer('funny')->default(0);
            $table->integer('surprise')->default(0);
            $table->integer('sad')->default(0);
            $table->integer('angry')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_comments');
    }
}
