<?php

namespace Modulatr\Loader;

use Modulatr\Loader\Menu\MenuItem;

/**
 * Class Module
 *
 * @package Modulatr\Loader
 */
abstract class Module implements ModuleContract
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $menus = [];

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->baseConfig(), $config);
        foreach ($this->getMenuTypes() as $type) {
            $methodName = $type . 'Menu';
            if (method_exists($this, $methodName)) {
                $this->menus[$type] = $this->{$methodName}();
            }
        }
    }

    /**
     * @return array
     */
    public function baseConfig(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getMenuTypes(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param $type
     * @return MenuItem|null
     */
    public function getMenu($type): ?MenuItem
    {
        return $this->menus[$type] ?? null;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [];
    }
}
