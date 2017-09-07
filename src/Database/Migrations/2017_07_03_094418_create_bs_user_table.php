<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bs_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('userId');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('groupId');
            $table->string('number')->nullable();
            $table->string('extension')->nullable();
            $table->string('mobile')->nullable();
            $table->string('emailAddress')->nullable();
            $table->string('department')->nullable();
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
        Schema::dropIfExists('bs_users');
    }
}
