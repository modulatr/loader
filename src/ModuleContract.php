<?php

namespace Modulatr\Loader;

use Modulatr\Loader\Menu\MenuItem;

/**
 * Interface ModuleContract
 *
 * @package Modulatr\Loader
 */
interface ModuleContract
{
    public function getId(): string;

    public function baseConfig(): array;

    public function getConfig(): array;

    public function getServiceProviders(): array;

    public function getMenu(string $type): ?MenuItem;

    public function getMenuTypes(): array;

    public function getDependencies(): array;
}
