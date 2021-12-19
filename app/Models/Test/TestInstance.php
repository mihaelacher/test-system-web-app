<?php

namespace App\Models\Test;

use App\Models\MainModel;
use Carbon\Carbon;

/**
 * App\Models\Test\TestInstance
 *
 * @property int $id
 * @property Carbon $active_from
 * @property Carbon $active_to
 * @property int $is_reexecutable
 * @property int $can_correct_answers
 * @property int $are_questions_randomized
 * @property int $are_answers_randomized
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $created_by
 * @property int $updated_by
 */
class TestInstance extends MainModel
{
    protected $table = 'test_instances';
}
