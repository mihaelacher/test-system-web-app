<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainGetRequest;
use App\Models\Test\TestHasVisibleUsers;
use Carbon\Carbon;

class TestExecutionStartRequest extends MainGetRequest
{
    public function authorize()
    {
        date_default_timezone_set('Europe/Sofia');
        $now = Carbon::now();

        return TestHasVisibleUsers::join('test_instances as ti',
            'ti.id', '=', 'test_has_visible_users.test_instance_id')
            ->where('test_has_visible_users.user_id', '=', $this->currentUser->id)
            ->where('ti.test_id', '=', request()->id)
            ->where('ti.active_from', '<=', $now)
            ->where('ti.active_to', '>=', $now)
            ->exists();
    }
}
