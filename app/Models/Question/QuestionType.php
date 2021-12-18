<?php

namespace App\Models\Question;

use App\Models\MainModel;

/**
 * App\Models\Question\QuestionType
 *
 * @property int $id
 * @property string $name
 */
class QuestionType extends MainModel
{
    protected $table = 'question_types';
}
