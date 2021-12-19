<?php

namespace App\Models\Test;

use App\Models\MainModel;
use Carbon\Carbon;

/**
 * App\Models\Test\TestHasVisibleUsers
 *
 * @property int $id
 * @property Carbon $active_from
 * @property Carbon $active_to
 * @property int $user_id
 * @property int $test_id
 */
class TestHasVisibleUsers extends MainModel
{
    protected $table = 'test_has_visible_users';
}
