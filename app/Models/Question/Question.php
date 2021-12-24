<?php

namespace App\Models\Question;

use App\Models\MainModel;
use App\Models\ModifiableModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Question\Question
 *
 * @property int $id
 * @property string $text
 * @property string $instruction
 * @property double $points
 * @property int $question_type_id
 * @property int $max_markable_answers
 * @property int $is_open
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Question extends MainModel
{
    protected $table = 'questions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Question\QuestionAnswer', 'question_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\Question\QuestionType', 'id', 'question_type_id');
    }
}
