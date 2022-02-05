<?php

namespace Jkbennemann\Features\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jkbennemann\Features\Database\Factories\FeatureFactory;
use Jkbennemann\Features\Models\Builders\FeatureBuilder;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Traits\HasSlug;

/**
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $status
 * @property Carbon $created_at
 */
class Feature extends Model
{
    use HasFactory;
    use HasSlug;

    protected $table = 'feature';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'int',
    ];

    public function isActive(): bool
    {
        return $this->status === FeatureStatus::ACTIVE;
    }

    public function activate(): bool
    {
        $this->status = FeatureStatus::ACTIVE;

        return $this->save();
    }

    public function deactivate(): bool
    {
        $this->status = FeatureStatus::INACTIVE;

        return $this->save();
    }

    public function parties(): BelongsToMany
    {
        return $this->belongsToMany(
            Party::class,
            'feature_party',
        );
    }

    public function newEloquentBuilder($query): FeatureBuilder
    {
        return new FeatureBuilder($query);
    }

    public static function newFactory(): FeatureFactory
    {
        return new FeatureFactory();
    }
}
