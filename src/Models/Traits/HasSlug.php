<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

trait HasSlug
{
    public static string $slug = 'slug';
    public static string $slugFrom = 'name';

    public static function bootHasSlug(): void
    {
        static::creating(static function (Model $model) {
            $model->{HasSlug::$slug} = self::normalize($model->{HasSlug::$slugFrom});

            Gate::define($model->{HasSlug::$slug}, function($user) use ($model){
               return $user->hasFeature($model->slug);
            });
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
