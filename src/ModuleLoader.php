<?php

namespace Modulatr\Loader;

use Modulatr\Loader\Exceptions\ModuleLoadedException;
use Modulatr\Loader\Exceptions\UnknownMenuTypeException;

/**
 * Class ModuleLoader
 *
 * @package Modulatr\Loader
 */
class ModuleLoader
{
    /**
     * @var ModuleContract[]
     */
    private $modules = [];

    /**
     * @var array
     */
    private $serviceProviders = [];

    /**
     * @var ModuleContract
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
     * @param ModuleContract[] $modules
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
     * @return ModuleContract
     */
    private function getModuleFromConfigPair($value, $key): ModuleContract
    {
        if (is_array($value) && is_string($key)) {
            $module = new $key($value);
        } else {
            $module = new $value();
        }
        return $module;
    }

    /**
     * @param ModuleContract $module
     */
    private function loadModule(ModuleContract $module): void
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
     * @param ModuleContract $module
     */
    private function loadModuleDependencies(ModuleContract $module)
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
     * @param ModuleContract $module
     */
    private function loadModuleIgnoreDuplicates(ModuleContract $module): void
    {
        if (!array_key_exists($module->getId(), $this->modules)) {
            $this->modules[$module->getId()] = $module;
            $this->loadModuleDependencies($module);
            $this->addModuleServiceProviders($module);
            $this->addModuleMenuItems($module);
        }
    }

    /**
     * @param ModuleContract $module
     */
    private function addModuleServiceProviders(ModuleContract $module): void
    {
        $providers = $this->serviceProviders + $module->getServiceProviders();
        $this->serviceProviders = array_unique($providers);
    }

    /**
     * @param ModuleContract $module
     */
    public function addModuleMenuItems(ModuleContract $module): void
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
     * @return ModuleContract|null
     */
    private function getCurrentModuleNameFromRoute(array $parts): ?ModuleContract
    {
        foreach ($parts as $part) {
            if (in_array($part, array_keys($this->modules))) {
                return $this->modules[$part];
            }
        }
        return null;
    }

    /**
     * @return ModuleContract[]
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
     * @return ModuleContract|null
     */
    public function getCurrentModule(): ?ModuleContract
    {
        return $this->currentModule;
    }
}
