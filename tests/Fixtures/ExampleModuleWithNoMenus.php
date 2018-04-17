<?php

namespace ModulatrTests\Loader\Fixtures;

use Modulatr\Loader\Module;

class ExampleModuleWithNoMenus extends Module
{

    public function getId(): string
    {
        return 'no-menus';
    }

    public function getServiceProviders(): array
    {
        return [];
    }
}
