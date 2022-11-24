<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('messages', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('from')->unsigned();
          $table->integer('to')->unsigned();
          // $table->integer('read')->unsigned()->default(0);
          // $table->boolean('read')->default(false);
          $table->boolean('read')->default(false);
          $table->integer('totalUnreadMsgTo')->unsigned()->default(0);
          $table->text('text')->nullable();
          $table->string('image_url')->nullable();
          $table->string('video_url')->nullable();

          $table->string('status')->default('created');

          $table->integer('reply_id')->nullable();

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
        Schema::dropIfExists('messages');
    }
}
