<?php

namespace ModulatrTests\Loader\Fixtures;

use Modulatr\Loader\Menu\MenuItem;
use Modulatr\Loader\Module;

class ExampleThreeModule extends Module
{

    public function getId(): string
    {
        return 'example-three';
    }

    public function getMenuTypes(): array
    {
        return ['admin'];
    }

    public function adminMenu(): MenuItem
    {
        return MenuItem::build('Example Three', 'example');
    }

    public function getServiceProviders(): array
    {
        return ['test', 'another'];
    }
}
