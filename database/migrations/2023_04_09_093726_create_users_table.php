<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("username");
            $table->string("firstname");
            $table->string("lastname");
            $table->string("email");
            $table->string("bio");
            $table->string("contact");
            $table->string("age");
            $table->string("gender");
            $table->string("matchgender");
            $table->string("profile");
            $table->string("password");
            $table->boolean("ishidden");
            $table->timestamp("date_verified");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
