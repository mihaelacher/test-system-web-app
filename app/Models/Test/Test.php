<?php

namespace App\Models\Test;

use App\Models\MainModel;
use App\Models\ModifiableModel;
use Carbon\Carbon;

/**
 * App\Models\Test\Test
 *
 * @property int $id
 * @property string $name
 * @property string $intro_text
 * @property int $max_duration
 * @property int $is_visible_for_admins
 * @property int $is_reexecutable
 * @property int $can_correct_answers
 * @property int $are_questions_randomized
 * @property int $are_answers_randomized
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $created_by
 * @property int $updated_by
 *
 */
class Test extends MainModel
{
    protected $table = 'tests';
}
