<?php

namespace App\Models\Test;

use App\Models\MainModel;
use Carbon\Carbon;

/**
 * App\Models\Test\Test
 *
 * @property int $id
 * @property string $name
 * @property string $intro_text
 * @property int $max_duration
 * @property int $is_public
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
