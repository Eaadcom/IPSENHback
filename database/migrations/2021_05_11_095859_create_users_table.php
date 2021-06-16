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
            $table->string('password')->notNullable();
            $table->string('first_name')->notNullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->notNullable();
            $table->string('gender')->notNullable();
            $table->date('date_of_birth')->notNullable();
            $table->text('about_me')->notNullable();
            $table->integer('age_range_bottom')->notNullable();
            $table->integer('age_range_top')->notNullable();
            $table->string('interest');

            $table->index(['id', 'email']);
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
