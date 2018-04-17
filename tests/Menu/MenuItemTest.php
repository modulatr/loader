<?php

namespace ModulatrTests\Loader\Menu;

use Modulatr\Loader\Menu\MenuItem;
use ModulatrTests\Loader\TestCase;

class MenuItemTest extends TestCase
{

    public function testItCanInstantiateAMenuItem()
    {
        $menuItem = new MenuItem('Example', 'url');

        $this->assertMenuItemState($menuItem, 'Example', 'url');
        $this->assertMenuItemHasNoSubMenu($menuItem);
    }

    public function testItCanBuildAMenuItem()
    {
        $menuItem = MenuItem::build('Example', 'url');

        $this->assertMenuItemState($menuItem, 'Example', 'url');
        $this->assertMenuItemHasNoSubMenu($menuItem);
    }

    public function testItCanCreateASeparatorFromAMenuItem()
    {
        $menuItem = new MenuItem();
        $menuItem->setSeparator();
        $this->assertTrue($menuItem->isSeparator());
    }

    public function testItCanCreateASeparator()
    {
        $menuItem = MenuItem::separator();
        $this->assertTrue($menuItem->isSeparator());
    }

    public function testItCanCreateANestedMenu()
    {
        $menuItem = new MenuItem('Example');
        $menuItem = $menuItem->addSubMenuItem(new MenuItem('Tier 1', 'tier-1'));

        $this->assertMenuItemState($menuItem, 'Example', '#');
        $this->assertMenuItemHasSubMenu($menuItem);
        $item = $menuItem->getSubMenu()[0];
        $this->assertMenuItemState($item, 'Tier 1', 'tier-1');
        $this->assertMenuItemHasNoSubMenu($item);
    }

    public function testItCanBuildANestedMenu()
    {
        $menuItem = MenuItem::build('Example')->addSubMenuItem(MenuItem::build('Tier 1', 'tier-1'));

        $this->assertMenuItemState($menuItem, 'Example', '#');
        $this->assertMenuItemHasSubMenu($menuItem);
        $item = $menuItem->getSubMenu()[0];
        $this->assertMenuItemState($item, 'Tier 1', 'tier-1');
        $this->assertMenuItemHasNoSubMenu($item);
    }

    public function testItCanCreateSubNestedMenu()
    {
        $menuItem = MenuItem::build('Example')
            ->addSubMenuItem(
                MenuItem::build('Tier 1')
                    ->addSubMenuItem(
                        MenuItem::build('Tier 2', 'tier-2')
                    )
            );

        $this->assertMenuItemState($menuItem, 'Example', '#');
        $this->assertMenuItemHasSubMenu($menuItem);

        $item = $menuItem->getSubMenu()[0];
        $this->assertMenuItemState($item, 'Tier 1', '#');
        $this->assertMenuItemHasSubMenu($item);

        $subItem = $item->getSubMenu()[0];
        $this->assertMenuItemState($subItem, 'Tier 2', 'tier-2');
        $this->assertMenuItemHasNoSubMenu($subItem);
    }

    /**
     * @param MenuItem $menuItem
     * @param $text
     * @param $url
     */
    private function assertMenuItemState(MenuItem $menuItem, $text, $url): void
    {
        $this->assertEquals($text, $menuItem->getText());
        $this->assertEquals($url, $menuItem->getUrl());
        $this->assertFalse($menuItem->isSeparator());

    }

    /**
     * @param MenuItem $menuItem
     */
    private function assertMenuItemHasSubMenu(MenuItem $menuItem): void
    {
        $this->assertTrue($menuItem->hasSubMenu());
        $this->assertNotEmpty($menuItem->getSubMenu());
    }

    /**
     * @param MenuItem $menuItem
     */
    private function assertMenuItemHasNoSubMenu(MenuItem $menuItem): void
    {
        $this->assertFalse($menuItem->hasSubMenu());
        $this->assertEmpty($menuItem->getSubMenu());
    }
}
