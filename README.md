# Laravel Features

This packages provides a simple to use possibility to introduce functionality (`features`) 
to specific users or groups of users (`parties`).

This concept is also known as FeatureFlags or FeatureToggles.

## Installation

You can install the package via composer:

```bash
composer require jkbennemann/laravel-features
```

After you have installed the package run the following command start the 
installation routine

```bash
#Interactive installation
php artisan feature:install

#Command if you are using uuids for your model
php artisan feature:install --uuid

#Command if you are using unsigned big integer (id)
php artisan feature:install --id
```

### Last steps

Migrate database files
```bash
php artisan migrate
```
Adding `HasFeatures` trait to your `User` model 
```php
//..
use Jkbennemann\Features\Models\Traits\HasFeatures;

class User extends Model
{
    use HasFeatures;
    //..
}
```

_After these steps you're good to go._

## Common Use-Cases
### 1. A/B testing
1. Add you `features` which should be tested 
2. Create two `parties` and add users to them
3. Assign features to the parties or single users
### 2. A group of Beta-Testers
1. Add you `features` which should be beta-tested
2. Create a new `party` fpr you beta-testers
3. Assign the users to that party
### 3. Preparation for an upcoming feature
1. Add a new `feature` which should be `INACTIVE` for now
2. Complete development and optionally create a special group for users e.g.  
developers that should still be able to access that new feature
3. Extend that feature to beta-testers or remove feature-switch
### 4. Functionality for specific users like administrators
1. Create a new `party` for administrators
2. Assign all `features` which should be explicitly available for them to that party
3. Add all relevant users to that party.

## Usage

```php
use \Jkbennemann\Features\Models\Enums\FeatureStatus;
use \Jkbennemann\Features\Models\Feature;

$feature = Features::create([
    'name' => 'Functionality A',
    'description' => 'This is a new feature', //optional
    'status' => FeatureStatus::ACTIVE //optional, defaults to INACTIVE
]);

$party = Party::create([
    'name' => 'Beta Testers',
    'description' => 'This is a new party', //optional
    'status' => FeatureStatus::ACTIVE //optional, defaults to INACTIVE
]);

$feature->activate();       //activates a feature
$feature->deactivate();     //deactivates a feature

$party->addFeature($feature);       //assigns a feature to a party
$party->removeFeature($feature);    //removes a feature from a party

$active = $user->hasFeature($feature);        //user has the feature which is active
$active = $user->hasFeature('feature-slug');  //you may provide the slug of a feature
$active = $user->hasFeature('feature-slug', false); //provide false ignore the check for active features
$active = $user->hasFeatureThroughParty('feature-slug');    //checks if a feature is granted through a party

$features = $user->allFeatures();   //returns all features
$features = $user->allFeatures(false);   //returns all features without checking the status

$user->giveFeature('feature-slug');     //add specific feature to a user
$user->removeFeature('feature-slug');   //remove specific feature

$user->joinParty('party-slug');     //add a user to a party
$user->addToParty('party-slug');    //add a user to a party
$user->leaveParty('party-slug');    //remove a user from a party

$user->belongsToParty('party-slug');    //checks if the user belongs to the party
$user->inParty('party-slug');           //checks if the user belongs to the party
```

## Commands

```bash
# Create a new feature/party
php artisan feature:add {name} {description?} {--status}
php artisan party:add {name} {description?} {--status}

# List features/parties
php artisan feature:list
php artisan party:list

# Activate a feature/party
php artisan feature:activate {id|slug}
php artisan party:activate {id|slug}

# Deactivate a feature/party
php artisan feature:deactivate {id|slug}
php artisan party:deactivate {id|slug}
```

## Gates

```php
# Check feature
$user->can('feature-slug');   //allows to check using laravel gates
$user->can('feature-slug', true);    //validates if feature is ACTIVE
$user->can('feature-slug', false);    //ignores status of feature
```


## Ideas

- [x] Blade directives for `@feature`, `@party`
- [x] Command to update feature status
- [x] Gate support
- [x] Support for UUIDs for `User` model
- [ ] Middleware to secure requests for features/parties
- [ ] Feature expiration to tackle [carying costs](https://martinfowler.com/articles/feature-toggles.html#WorkingWithFeature-flaggedSystems)
- [ ] Management for Parties/Features using Livewire

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jakob Bennemann](https://github.com/jkbennemann)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
