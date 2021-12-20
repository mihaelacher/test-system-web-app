<?php

namespace App\Models\Test;

use App\Models\MainModel;

/**
 * App\Models\Test\TestExecutionAnswer
 *
 * @property int $id
 * @property int $test_question_answer_id
 * @property float $response_numeric
 * @property string $response_text_short
 * @property string $response_text_long
 * @property int $is_correct
 */
class TestExecutionAnswer extends MainModel
{
    protected $table = 'test_execution_answers';

    public function answer()
    {
        return $this->hasOne('App\Models\Question\QuestionAnswer', 'id', 'question_answer_id');
    }

    public function question()
    {
        return $this->hasOne('App\Models\Question\Question', 'id', 'question_id');
    }
}
