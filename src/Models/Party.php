<?php

namespace Jkbennemann\Features\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User;
use Jkbennemann\Features\Database\Factories\PartyFactory;
use Jkbennemann\Features\Models\Builders\PartyBuilder;
use Jkbennemann\Features\Models\Traits\HasSlug;

/**
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $status
 * @property Collection $features
 * @property Carbon $created_at
 */
class Party extends Model
{
    use HasFactory;
    use HasSlug;

    protected $table = 'party';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'int',
    ];

    public function addFeature(Feature $feature): void
    {
        $this->features()->attach($feature);
        $this->load('features');
    }

    public function removeFeature(Feature $feature): bool
    {
        $result = (bool) $this->features()->detach($feature->id);
        $this->load('features');

        return $result;
    }

    public function hasFeature(Feature|string $feature): bool
    {
        return match (true) {
            $feature instanceof Feature => $this->features->contains('id', $feature->getKey()),
            default => $this->features->contains('slug', $feature),
        };
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_party',
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'party_user',
        );
    }

    public function newEloquentBuilder($query): PartyBuilder
    {
        return new PartyBuilder($query);
    }

    public static function newFactory(): PartyFactory
    {
        return new PartyFactory();
    }
}
