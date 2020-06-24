# Blade Script

A package to easily add script to your blade components.

```php
<button class="btn" onlick="myFunction()">{{ $slot }}</button>

<x-script>
myFunction() {
    // Do something.
}
</x-script>
```

## Introduction

In case you want to add javascript functionality to a blade component, you can
write it directly in the script tag. However, the script will then not be
minified and if a component is used multiple times, the script tag will also be
included multiple times.

Blade Script solves these problems. The javascript code can be minified in
production and each script tag is only included once all without running a
compiler separately. Also only required scripts are included.

Additionally there is the possibility to use transpilers like
[babel](https://babeljs.io/).

## Installation

The package can be easily installed via composer.

```shell
composer requrie cbl/blade-script
```

Now the necessary assets must be published. This includes the script.php config
and the storage folder where the compiled scripts are stored.

```shell
php artisan vendor:publish --provider="BladeScript\ServiceProvider"
```

## Include Styles

The blade component `x-scripts` includes all required scripts, so it may be
placed at the very bottom of your **body**.

```php
<body>
    ...

    <x-scripts />
</body>
```

## Usage

Each blade component can contain exactly one `x-script` component. Your scripts
can then be written inside the wrapper like so.

```php
<button class="btn" onlick="myFunction()">{{ $slot }}</button>

<x-script>
myFunction() {
    // Do something.
}
</x-script>
```

## Write Transpiler

You can easily add transpilers to the compiler, the following example explains
how to create and include a transpiler.

First you have to create a transpiler class that implements the
`BladeScript\Contracts\Transpiler` contract.

```php

namespace BladeBabel;

use Babel\Transpiler as Babel;
use BladeScript\Contracts\Transpiler;

class BabelTranspiler implements Transpiler
{
    public function transpile($script)
    {
        return Babel::transform($script);
    }
}
```

The transpiler can now be added to the compiler in your service provider like
so:

```php

namespace BladeBabel;

use Illuminate\Support\ServiceProvider;

class BabelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->afterResolving('script.compiler', function ($compiler) {
            $compiler->addTranspiler(new Transpiler);
        });
    }
}
```
