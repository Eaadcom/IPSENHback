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
            $table->string('api_token')->nullable();

            $table->string('first_name')->notNullable();
            $table->string('middle_name')->notNullable();
            $table->string('last_name')->notNullable();
            $table->date('date_of_birth')->notNullable();
            $table->text('about_me')->notNullable();
            $table->integer('age_range_bottom')->notNullable();
            $table->integer('age_range_top')->notNullable();
            $table->integer('max_distance')->notNullable();
            $table->string('interest');

            $table->index(['id', 'email', 'api_token']);
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
