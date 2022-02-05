# Laravel Features

This packages provides a simple to use possibility to introduce functionality (`features`) 
to specific users or groups of users (`parties`)

## Installation

You can install the package via composer:

```bash
composer require jkbennemann/laravel-features
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-features-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-features-config"
```

This is the contents of the published config file:

```php
return [
    'middleware' => [
        'mode' => 'abort',
        'redirect_route' => '/',
        'status_code' => 404,
        'message' => '',
    ],
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-features-views"
```

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

$user->giveFeature('feature-slug');     //add specific feature to a user
$user->removeFeature('feature-slug');   //remove specific feature

$user->joinParty('party-slug');     //add a user to a party
$user->addToParty('party-slug');    //add a user to a party
$user->leaveParty('party-slug');    //remove a user from a party

$user->belongsToParty('party-slug');    //checks if the user belongs to the party
$user->inParty('party-slug');           //checks if the user belongs to the party
```

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
