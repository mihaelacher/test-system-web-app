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

    const SINGLE_CHOICE = 1;
    const MULTIPLE_CHOICE = 2;
    const NUMERIC = 3;
    const TEXT_LONG = 4;
    const TEXT_SHORT = 4;

    const CLOSED_QUESTIONS = [self::SINGLE_CHOICE, self::MULTIPLE_CHOICE];
}
