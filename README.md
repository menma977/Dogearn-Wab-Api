# Dogearn
 App to Budgeting all you need


```shell
composer require laravel/ui --dev

php artisan ui vue --auth

npm install && npm run dev

composer require laravel/passport
```

##### config passport
```shell
php artisan migrate

php artisan passport:install
```
###### App\User
```php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```

###### AuthServiceProvider
```php
use Laravel\Passport\Passport;

protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
 ];
 
 public function boot()
{
    $this->registerPolicies();

    Passport::routes();
}
```

##### config/auth.php
###### TokenGuard
```
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

[passport link](https://laravel.com/docs/master/passport)
##### end config passport