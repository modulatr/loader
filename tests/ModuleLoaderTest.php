<?php

namespace ModulatrTests\Loader;

use Modulatr\Loader\ModuleLoader;
use ModulatrTests\Loader\Fixtures\ExampleModule;
use ModulatrTests\Loader\Fixtures\ExampleModuleWithDependencies;
use ModulatrTests\Loader\Fixtures\ExampleModuleWithNoMenus;
use ModulatrTests\Loader\Fixtures\ExampleThreeModule;
use ModulatrTests\Loader\Fixtures\ExampleTwoModule;
use ModulatrTests\Loader\TestCase;

class ModuleLoaderTest extends TestCase
{
    /** @test */
    public function itCanCreateAnEmptyModuleManager()
    {
        $manager = new ModuleLoader([], []);
        $modules = $manager->getModules();
        $this->assertCount(0, $modules);
    }

    /** @test */
    public function itCanCreateAModuleManagerWithOneModule()
    {
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModule::class,
            ]
        ]);

        $modules = $manager->getModules();

        $this->assertCount(1, $modules);
        $this->assertInstanceOf(ExampleModule::class, $modules['example']);
    }

    /**
     * @test
     * @expectedException \Modulatr\Loader\Exceptions\ModuleLoadedException
     */
    public function itCanNotLoadAModuleWithTheSameId()
    {
        new ModuleLoader([], [
            'modules' => [
                ExampleModule::class,
                ExampleModule::class,
            ]
        ]);
    }

    /** @test */
    public function itCanCreateAModuleManagerWithOneModuleWithConfig()
    {
        $moduleConfig = ['foo' => 'bar'];
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModule::class => $moduleConfig
            ]
        ]);

        $modules = $manager->getModules();
        $this->assertEquals($moduleConfig, $modules['example']->getConfig());
    }

    /** @test */
    public function itLoadsAModulesServiceProviders()
    {
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModule::class
            ]
        ]);

        $providers = $manager->getServiceProviders();

        $this->assertCount(1, $providers);
    }

    /** @test */
    public function itCanLoadTwoModules()
    {
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModule::class,
                ExampleTwoModule::class,
            ]
        ]);

        $modules = $manager->getModules();
        $this->assertCount(2, $modules);
        $this->assertInstanceOf(ExampleModule::class, $modules['example']);
        $this->assertInstanceOf(ExampleTwoModule::class, $modules['example-two']);
    }

    /** @test */
    public function itDoesNotDuplicateServiceProvidersWhenLoadingTwoModules()
    {
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModule::class,
                ExampleTwoModule::class,
            ]
        ]);

        $serviceProviders = $manager->getServiceProviders();
        $this->assertCount(1, $serviceProviders);
        $this->assertContains('test', $serviceProviders);
    }

    /** @test */
    public function itLoadsAllServiceProvidersAcrossManyModules()
    {
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModule::class,
                ExampleTwoModule::class,
                ExampleThreeModule::class,
            ]
        ]);

        $serviceProviders = $manager->getServiceProviders();
        $this->assertCount(2, $serviceProviders);
        $this->assertContains('test', $serviceProviders);
        $this->assertContains('another', $serviceProviders);
    }

    /** @test */
    public function itCanDetectTheCurrentModuleFromTheRouteName()
    {
        $manager = new ModuleLoader(['example', 'one'], [
            'modules' => [
                ExampleModule::class,
                ExampleTwoModule::class,
            ]
        ]);

        $module = $manager->getCurrentModule();

        $this->assertInstanceOf(ExampleModule::class, $module);
    }

    /** @test */
    public function itCanDetectTheCurrentModuleFromTheRouteNameWithPrefixes()
    {
        $manager = new ModuleLoader(['admin', 'example', 'one'], [
            'modules' => [
                ExampleModule::class,
                ExampleTwoModule::class,
            ],
            'prefixes' => [
                'admin' => 'admin'
            ]
        ]);

        $module = $manager->getCurrentModule();

        $this->assertInstanceOf(ExampleModule::class, $module);
    }

    /** @test */
    public function itCanLoadDependentModules()
    {
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModuleWithDependencies::class,
            ],
        ]);

        $modules = $manager->getModules();

        $this->assertCount(2, $modules);
        $this->assertArrayHasKey('example', $modules);
        $this->assertArrayHasKey('example-dependencies', $modules);
        $this->assertInstanceOf(ExampleModuleWithDependencies::class, $modules['example-dependencies']);
        $this->assertInstanceOf(ExampleModule::class, $modules['example']);
    }

    /** @test */
    public function itCanLoadModuleMenuItems()
    {
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModule::class,
                ExampleTwoModule::class,
                ExampleThreeModule::class,
            ],
        ]);

        $menuItems = $manager->getMenuItems('admin');

        $this->assertCount(3, $menuItems);
    }

    /**
     * @test
     * @expectedException \Modulatr\Loader\Exceptions\UnknownMenuTypeException
     */
    public function testItCanNotLoadAnUnknownMenu()
    {
        $manager = new ModuleLoader([], [
            'modules' => [
                ExampleModuleWithNoMenus::class,
            ],
        ]);
        $manager->getMenuItems('admin');
    }
}
