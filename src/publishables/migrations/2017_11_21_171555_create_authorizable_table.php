<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthorizableTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_users', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'role_id']);
        });
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('permissions');
            $table->timestamps();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['user_id', 'role_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

        });
    }
}
