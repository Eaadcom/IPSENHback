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
            $table->string('password')->nullable(false);

            $table->string('first_name')->nullable(false);
            $table->string('middle_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('gender')->nullable(false);
            $table->date('date_of_birth')->nullable(false);
            $table->text('about_me')->nullable(false);
            $table->integer('age_range_bottom')->nullable(false);
            $table->integer('age_range_top')->nullable(false);
            $table->integer('max_distance')->nullable(false);
            $table->string('interest');

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
