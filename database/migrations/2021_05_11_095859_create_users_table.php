<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('email')->unique();
            $table->string('password')->notNull();
            $table->date('date_of_birth')->notNull();
            $table->text('about_me');
            $table->integer('age_range_bottom');
            $table->integer('age_range_top');
            $table->string('interest');
            $table->integer('max_distance');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *do
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
