<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('middle_name')->default('')->nullable()->change();
            $table->string('interest')->default('any')->change();
            $table->integer('age_range_bottom')->default(100)->nullable()->change();
            $table->integer('age_range_top')->default(100)->nullable()->change();
            $table->integer('max_distance')->default(0)->nullable()->change();
            $table->date('date_of_birth')->change();
            $table->dropColumn('api_token');
            $table->text('about_me')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->change();
        });
    }
}
