<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestHasVisibleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_has_visible_users', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedInteger('test_instance_id');
            $table->unsignedInteger('user_id');

            $table->primary('id');
            $table->foreign('test_instance_id')->references('id')->on('test_instances');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_has_visible_users');
    }
}
