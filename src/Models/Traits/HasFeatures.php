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
        $objects = collect($features)->filter(function ($item) {
            return $item instanceof Feature;
        });

        $features = $this->getAllFeatures(
            features: Arr::flatten($features)
        );

        $objects->each(function ($item) use ($features) {
            $features->add($item);
        });

        $this->features()->saveMany($features->unique());

        return $this;
    }

    public function removeFeature(...$features): self
    {
        $objects = collect($features)->filter(function ($item) {
            return $item instanceof Feature;
        });

        $features = $this->getAllFeatures(
            features: Arr::flatten($features)
        );

        $objects->each(function ($item) use ($features) {
            $features->add($item);
        });

        $this->features()->detach($features->unique());
        $this->load('features');

        return $this;
    }

    public function updateFeatures(...$features): self
    {
        $this->features()->detach();

        return $this->giveFeature($features);
    }

    public function hasFeature(Feature|string $feature, bool $activeOnly = true): bool
    {
        return $this->hasFeatureThroughParty(
            feature: $feature,
            activeOnly: $activeOnly,
        ) || $this->hasFeatureDirect(
            feature: $feature,
            activeOnly: $activeOnly,
        );
    }

    public function hasFeatureDirect(Feature|string $feature, bool $activeOnly = true): bool
    {
        if ($feature instanceof Feature) {
            if ($activeOnly && ! $feature->isActive()) {
                return false;
            }

            return $this->features->contains($feature);
        }

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

    public function hasFeatureThroughParty(Feature|string $feature, bool $activeOnly = true): bool
    {
        if ($feature instanceof Feature) {
            if ($activeOnly && ! $feature->isActive()) {
                return false;
            }

            foreach ($feature->parties as $party) {
                if ($this->parties->contains($party)) {
                    return true;
                }
            }

            return false;
        }

        if ($activeOnly) {
            $feature = Feature::with(['parties'])->active()->slug($feature)->first();
        } else {
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
            if ($party instanceof Party) {
                return $this->parties->contains($party);
            }

            return $this->parties->contains('slug', $party);
        }

        return false;
    }

    public function leaveParty(Party|string $party): self
    {
        if (! ($party instanceof Party)) {
            $party = $this->getAllparties(
                parties: [$party]
            );
        }

        $this->parties()->detach($party);
        $this->load('parties');

        return $this;
    }

    public function joinParty(...$parties): self
    {
        $objects = collect($parties)->filter(function ($item) {
            return $item instanceof Party;
        });

        $parties = $this->getAllparties(
            parties: Arr::flatten($parties)
        );

        $objects->each(function ($item) use ($parties) {
            $parties->add($item);
        });


        $this->parties()->saveMany($parties->unique());
        $this->load('parties');

        return $this;
    }

    public function addToParty(Party|string $party): self
    {
        return $this->joinParty(
            $party,
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

    public function partyHasFeature(Feature|string $feature, bool $activeOnly = true): bool
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
