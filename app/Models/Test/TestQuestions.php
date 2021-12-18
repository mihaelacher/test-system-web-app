<?php

namespace App\Models\Test;

use App\Models\MainModel;

/**
 *  App\Models\Test\TestQuestions
 *
 * @property int $id
 * @property int $test_id
 * @property int $question_id
 * @property int $order_num
 */
class TestQuestions extends MainModel
{
    protected $table = 'test_questions';
}
