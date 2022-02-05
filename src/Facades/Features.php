<?php

namespace Jkbennemann\Features\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jkbennemann\Features\Models\Features
 */
class Features extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-features';
    }
}
