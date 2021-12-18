<?php

namespace App\Models\Test;

use App\Models\MainModel;

/**
 * App\Models\Test\TestHasVisibleUsers
 *
 * @property int $id
 * @property int $user_id
 * @property int $test_id
 */
class TestHasVisibleUsers extends MainModel
{
    protected $table = 'test_has_visible_users';
}
