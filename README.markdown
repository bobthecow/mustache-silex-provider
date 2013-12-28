# MustacheServiceProvider

`MustacheServiceProvider` provides [Mustache][mustache] integration for the
[Silex][silex] application micro-framework.

 [mustache]: http://github.com/bobthecow/mustache.php
 [silex]:    http://silex.sensiolabs.org


## Installation

Add `mustache/silex-provider` to your project's `composer.json`:

```json
{
    "require": {
        "mustache/silex-provider": "~1.0"
    }
}
```

And install:

```
php composer.phar install
```


## Configuration

 * `mustache.path` (optional): Path to the directory containing Mustache
   template files.

 * `mustache.partials_path` (optional): Path to the directory containing
   Mustache partial template files. If none is specified, this will default to
   `mustache.path`.

 * `mustache.partials` (optional): An associative array of template names to
   template contents. Use this if you want to define partials inline.

 * `mustache.helpers` (optional): An associative array of Mustache helpers.
   See [the Mustache.php helpers documentation][helpers] for more information.

 * `mustache.options` (optional): An associative array of Mustache options. See
   [the Mustache.php documentation][options] for more information.

 [helpers]: https://github.com/bobthecow/mustache.php/wiki#helpers
 [options]: https://github.com/bobthecow/mustache.php/wiki


## Services

 * `mustache.loader` (optional): A Mustache template loader instance. This
   loader will use the `mustache.path` option you provided. You can also replace
   the loader with something awesome of your own.

 * `mustache.partials_loader` (optional): The Mustache template loader used to
   load partials. By default, this will load templates from either your the
   `mustache.partials_path` or `mustache.partials` configuration options. You
   can also replace the partials loader with another loader of your choice.


## Registering

```php
<?php

$app->register(new Mustache\Silex\Provider\MustacheServiceProvider, array(
    'mustache.path' => __DIR__.'/../views',
    'mustache.options' => array(
        'cache' => __DIR__.'/../tmp/cache/mustache',
    ),
));
```


## Usage

The Mustache provider provides a `mustache` service:

```php
<?php

$app->get('/hello/{name}', function ($name) use($app) {
    return $app['mustache']->render('hello', array(
        'name' => ucfirst($name),
    ));
});
```

This will render the `hello.mustache` file from your application's `views`
directory.


## Template Loading

The Mustache service provider ships with a Filesystem template loader enabled by
default. All you have to do to get things off the ground is set a
`mustache.path` option to tell it where your templates live. But you can swap
that loader out for [any other Mustache Loader][loader].

For +10 Awesome, you should check out the Inline template loader:

```php
<?php

// ...

$app->register(new MustacheServiceProvider, array(
    'mustache.loader' => new Mustache_Loader_InlineLoader(__FILE__, __COMPILER_HALT_OFFSET__)
));

$app->get('/{name}', function($name) use ($app) {
    return $app['mustache']->render('hello', compact('name'));
})
->value('name', 'world');

// ...

__halt_compiler();

@@ hello
Hello, {{ name }}!
```

 [loader]: https://github.com/bobthecow/mustache.php/wiki/Template-Loading#available-loaders


## The Trait

`Mustache\Silex\Application\MustacheTrait` adds a `render` helper to your app:

```php
<?php
use Silex\Application;

class MyApplication extends Application
{
    use \Mustache\Silex\Application\MustacheTrait;
}

$app = new MyApplication;
```

Now you can just call render:

```php
<?php

return $app->render('hello', array('name' => 'Justin'));
```

Or BYO Response:

```php
<?php
$response = new Response;
$response->setTtl(10);

return $app->render('hello', array('name' => 'Justin'), $response);
```

It also provides a `renderTemplate` helper which returns a rendered string
instead of a `Response` object.


## Customization

You can mess with Mustache before using it by extending the `mustache` service:

```php
<?php

$app['mustache'] = $app->share($app->extend('mustache', function ($mustache, $app) {
    $mustache->addHelper('app', $app);
    $mustache->setLogger($app['monolog']);

    return $mustache;
}));
```
