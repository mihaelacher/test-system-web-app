<?php

namespace App\Models\Test;

use App\Models\MainModel;
use Carbon\Carbon;

/**
 * App\Models\Tests\TestExecution
 *
 * @property int $id
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property float $result_points
 * @property string $creator_comment
 * @property int $executed_questions_count
 * @property int $correct_questions_count
 * @property int $test_instance_id
 * @property int $user_id
 */
class TestExecution extends MainModel
{
    protected $table = 'test_executions';

    public function testExecutionAnswers()
    {
        return $this->hasMany('App\Models\Test\TestExecutionAnswer', 'test_execution_id', 'id');
    }
}
