<?php

namespace ModulatrTests\Loader\Fixtures;

use Modulatr\Loader\Menu\MenuItem;
use Modulatr\Loader\Module;

class ExampleTwoModule extends Module
{

    public function getId(): string
    {
        return 'example-two';
    }

    public function getMenuTypes(): array
    {
        return ['admin'];
    }

    public function adminMenu(): MenuItem
    {
        return MenuItem::build('Example Two', 'example');
    }

    public function getServiceProviders(): array
    {
        return ['test'];
    }
}
