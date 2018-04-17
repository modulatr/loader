<?php

namespace ModulatrTests\Loader\Fixtures;

use Modulatr\Loader\Menu\MenuItem;
use Modulatr\Loader\Module;

class ExampleModule extends Module
{

    public function getId(): string
    {
        return 'example';
    }

    public function getMenuTypes(): array
    {
        return ['admin'];
    }

    public function adminMenu(): MenuItem
    {
        return MenuItem::build('Example', 'example')
            ->addSubMenuItem(MenuItem::build('Tier 1', 'tier-1'));
    }

    public function getServiceProviders(): array
    {
        return ['test'];
    }
}
