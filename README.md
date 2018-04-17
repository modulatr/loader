# Modulatr Loader

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/modulatr/loader/develop.svg?style=flat-square)](https://travis-ci.org/modulatr/loader)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/modulatr/loader.svg?style=flat-square)](https://scrutinizer-ci.com/g/modulatr/loader/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/modulatr/loader.svg?style=flat-square)](https://scrutinizer-ci.com/g/modulatr/loader)

Package for creating and loading modules in PHP projects.

## Installation via Composer

`composer require modulatr/loader`

## Usage

Modulatr is framework agnostic, although there are plans for implementing service providers for popular frameworks as
and when requested.

To start with, create a new Module.

```php
<?php

namespace App\Modules;

use Modulatr\Loader\Module;

class Example extends Module
{
    public function getId():string
    {
        return 'example';
    }
    
    public function getServiceProviders(): array 
    {
        return [];
    }
}

```

Next, load the module into the ModuleLoader class, with the URI parts as the first parameter and a config array as the
second:

```php
<?php

use Modulatr\Loader\ModuleLoader;
use App\Modules\Example;

$loader = new ModuleLoader(['example', 'index'], [
    'modules' => [
        Example::class,    
    ],
]);
```

In the above example, the URI passed to the module would be `/example/index`

If you are using a framework such as Laravel, you might want to create the array from the route name (which may be 
`example.index`) in order to not tie your modules to the URI directly.

Now, if you call `getCurrentModule()` on the loader, you will be returned your module class, as it's ID is `example` and
the URI array contains the string `example`.
