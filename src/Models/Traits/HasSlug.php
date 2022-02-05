<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    public static string $slug = 'slug';
    public static string $slugFrom = 'name';

    public static function bootHasSlug(): void
    {
        static::creating(static function (Model $model) {
            $model->{HasSlug::$slug} = self::normalize($model->{HasSlug::$slugFrom});
        });
    }

    public static function normalize(string $value): string
    {
        return Str::slug(Str::lower($value), '-');
    }

    public function normalisedSlug(string $value): string
    {
        return HasSlug::normalize($value);
    }
}
