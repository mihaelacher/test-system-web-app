<?php

namespace App\Models\Question;

use App\Models\MainModel;
use App\Models\ModifiableModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Question\QuestionAnswer
 *
 * @property int $id
 * @property int $order_num
 * @property string $value
 * @property int $is_correct
 * @property int $question_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $created_by
 * @property int $updated_by
 *
 */
class QuestionAnswer extends MainModel
{
    protected $table = 'question_answers';
}
