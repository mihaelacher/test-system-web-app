<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestExecutionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_execution_answers', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('question_answer_id')->nullable();
            $table->unsignedInteger('test_execution_id')->nullable();
            $table->decimal('response_numeric')->nullable();
            $table->string('response_text_short')->nullable();
            $table->text('response_text_long')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->foreign('question_answer_id')->references('id')->on('question_answers');
            $table->foreign('test_execution_id')->references('id')->on('test_executions');
            $table->index(['test_execution_id', 'question_answer_id']);
            $table->index(['test_execution_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_execution_answers');
    }
}
