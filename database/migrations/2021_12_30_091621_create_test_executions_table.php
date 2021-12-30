<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_executions', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->decimal('result_points')->nullable();
            $table->unsignedInteger('test_instance_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('test_instance_id')->references('id')->on('test_instances');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['test_instance_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_executions');
    }
}
