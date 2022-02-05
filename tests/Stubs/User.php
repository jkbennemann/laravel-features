<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Stubs;

use Illuminate\Foundation\Auth\User as AuthUser;
use Jkbennemann\Features\Models\Traits\HasFeatures;

class User extends AuthUser
{
    use HasFeatures;

    public $guarded = [];

    public $table = 'users';
}
