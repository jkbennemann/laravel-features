<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Jkbennemann\Features\Models\Feature;
use Jkbennemann\Features\Models\Party;

trait HasFeatures
{
    public function giveFeature(...$features): self
    {
        $features = $this->getAllFeatures(
            features: Arr::flatten($features)
        );

        $this->features()->saveMany($features);

        return $this;
    }

    public function removeFeature(...$features): self
    {
        $features = $this->getAllFeatures(
            features: Arr::flatten($features),
        );

        $this->features()->detach($features);
        $this->load('features');

        return $this;
    }

    public function updateFeatures(...$features): self
    {
        $this->features()->detach();

        return $this->giveFeature($features);
    }

    public function hasFeature(string $feature, bool $activeOnly = true): bool
    {
        return $this->hasFeatureThroughParty(
                feature: $feature,
                activeOnly: $activeOnly,
            ) || $this->hasFeatureDirect(
                feature: $feature,
                activeOnly: $activeOnly,
            );
    }

    public function hasFeatureDirect(string $feature, bool $activeOnly = true): bool
    {
        if ($activeOnly) {
            $feature = Feature::active()->slug($feature)->first();
        } else {
            $feature = Feature::slug($feature)->first();
        }

        if (is_null($feature)) {
            return false;
        }

        return $this->features->contains($feature);
    }

    public function hasFeatureThroughParty(string $feature, bool $activeOnly = true): bool
    {
        if ($activeOnly) {
            $feature = Feature::with(['parties'])->active()->slug($feature)->first();
        } else  {
            $feature = Feature::with(['parties'])->slug($feature)->first();
        }

        if (is_null($feature)) {
            return false;
        }

        foreach ($feature->parties as $party) {
            if ($this->parties->contains($party)) {
                return true;
            }
        }

        return false;
    }

    public function belongsToParty(...$parties): bool
    {
        return $this->inParty(...$parties);
    }

    public function inParty(...$parties): bool
    {
        foreach ($parties as $party) {
            if ($this->parties->contains('slug', $party)) {
                return true;
            }
        }

        return false;
    }

    public function leaveParty(...$parties): self
    {
        $parties = $this->getAllparties(
            parties: Arr::flatten($parties)
        );

        $this->parties()->detach($parties);
        $this->load('parties');

        return $this;
    }

    public function joinParty(...$parties): self
    {
        $parties = $this->getAllparties(
            parties: Arr::flatten($parties)
        );

        $this->parties()->saveMany($parties);
        $this->load('parties');

        return $this;
    }

    public function addToParty(...$parties): self
    {
        return $this->joinParty(
            parties: $parties,
        );
    }

    protected function getAllFeatures(array $features, bool $activeOnly = false): Collection
    {
        if ($activeOnly) {
            return Feature::active()->whereIn('slug', $features)->get();
        }

        return Feature::whereIn('slug', $features)->get();
    }

    protected function getAllParties(array $parties, bool $activeOnly = false): Collection
    {
        if ($activeOnly) {
            return Party::active()->whereIn('slug', $parties)->get();
        }

        return Party::whereIn('slug', $parties)->get();
    }

    public function partyHasFeature(string $feature, bool $activeOnly = true): bool
    {
        return $this->hasFeatureThroughParty(
            feature: $feature,
            activeOnly: $activeOnly,
        );
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_user'
        );
    }

    public function parties(): BelongsToMany
    {
        return $this->belongsToMany(
            Party::class,
            'party_user',
        );
    }
}
