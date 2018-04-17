<?php

namespace Modulatr\Loader;

use Modulatr\Loader\Exceptions\ModuleLoadedException;
use Modulatr\Loader\Exceptions\UnknownMenuTypeException;
use Modulatr\Loader\ModuleContract as Module;

/**
 * Class ModuleLoader
 *
 * @package Modulatr\Loader
 */
class ModuleLoader
{
    /**
     * @var Module[]
     */
    private $modules = [];

    /**
     * @var array
     */
    private $serviceProviders = [];

    /**
     * @var Module
     */
    private $currentModule;

    /**
     * @var array
     */
    private $prefixes;

    /**
     * @var array
     */
    private $menuTypes = [];

    /**
     * @var array
     */
    private $menuItems = [];

    /**
     * ModuleManager constructor.
     *
     * @param array $routeParts
     * @param array $config
     */
    public function __construct(array $routeParts, array $config)
    {
        $this->prefixes = $config['prefixes'] ?? [];
        $this->loadModules($config['modules'] ?? []);
        $this->currentModule = $this->getCurrentModuleNameFromRoute($routeParts);
    }

    /**
     * @param Module[] $modules
     */
    private function loadModules(array $modules): void
    {
        foreach ($modules as $key => $value) {
            $module = $this->getModuleFromConfigPair($value, $key);
            $this->loadModule($module);
        }
    }

    /**
     * @param $value
     * @param $key
     * @return Module
     */
    private function getModuleFromConfigPair($value, $key): Module
    {
        if (is_array($value) && is_string($key)) {
            $module = new $key($value);
        } else {
            $module = new $value();
        }
        return $module;
    }

    /**
     * @param Module $module
     */
    private function loadModule(Module $module): void
    {
        if (array_key_exists($module->getId(), $this->modules)) {
            throw new ModuleLoadedException();
        }
        $this->modules[$module->getId()] = $module;
        $this->loadModuleDependencies($module);
        $this->addModuleServiceProviders($module);
        $this->addModuleMenuItems($module);
    }

    /**
     * @param Module $module
     */
    private function loadModuleDependencies(Module $module)
    {
        $dependencies = $module->getDependencies();
        if (!empty($dependencies)) {
            $this->loadModulesIgnoreDuplicates($dependencies);
        }
    }

    /**
     * @param Module[] $modules
     */
    private function loadModulesIgnoreDuplicates(array $modules): void
    {
        foreach ($modules as $key => $value) {
            $module = $this->getModuleFromConfigPair($value, $key);
            $this->loadModuleIgnoreDuplicates($module);
        }
    }

    /**
     * @param Module $module
     */
    private function loadModuleIgnoreDuplicates(Module $module): void
    {
        if (!array_key_exists($module->getId(), $this->modules)) {
            $this->modules[$module->getId()] = $module;
            $this->loadModuleDependencies($module);
            $this->addModuleServiceProviders($module);
            $this->addModuleMenuItems($module);
        }
    }

    /**
     * @param Module $module
     */
    private function addModuleServiceProviders(Module $module)
    {
        $providers = $this->serviceProviders + $module->getServiceProviders();
        $this->serviceProviders = array_unique($providers);
    }

    /**
     * @param Module $module
     */
    public function addModuleMenuItems(Module $module)
    {
        foreach ($module->getMenuTypes() as $type) {
            if (!in_array($type, $this->menuTypes)) {
                $this->menuTypes[] = $type;
            }
            $menu = $module->getMenu($type);
            if (!isset($this->menuItems[$type])) {
                $this->menuItems[$type] = [];
            }
            $this->menuItems[$type][] = $menu;
        }
    }

    /**
     * @param array $parts
     * @return Module|null
     */
    private function getCurrentModuleNameFromRoute(array $parts): ?Module
    {
        foreach ($parts as $part) {
            if (in_array($part, array_keys($this->modules))) {
                return $this->modules[$part];
            }
        }
        return null;
    }

    /**
     * @return Module[]
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @param string $type
     * @return array
     */
    public function getMenuItems(string $type): array
    {
        if (!in_array($type, $this->menuTypes)) {
            throw new UnknownMenuTypeException();
        }
        return $this->menuItems[$type] ?? [];
    }

    /**
     * @return array
     */
    public function getServiceProviders(): array
    {
        return $this->serviceProviders;
    }

    /**
     * @return Module|null
     */
    public function getCurrentModule(): ?Module
    {
        return $this->currentModule;
    }
}
