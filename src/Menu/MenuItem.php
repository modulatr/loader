<?php

namespace Modulatr\Loader\Menu;

/**
 * Class MenuItem
 *
 * @package Modulatr\Loader\Menu
 */
class MenuItem
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $text;

    private $subMenu = [];
    /**
     * @var bool
     */
    private $separator = false;

    /**
     * LinkItem constructor.
     *
     * @param string $text
     * @param string $url
     */
    public function __construct(string $text = '', string $url = '#')
    {

        $this->text = $text;
        $this->url = $url;
    }

    /**
     * @param string $text
     * @param string $url
     * @return static
     */
    public static function build(string $text = '', string $url = '#'): self
    {
        return new static($text, $url);
    }

    /**
     * Create a separator Menu Item
     *
     * @return MenuItem
     */
    public static function separator(): self
    {
        $item = new static();
        $item->setSeparator();
        return $item;
    }

    /**
     * Set if the item is a separator
     */
    public function setSeparator()
    {
        $this->separator = true;
    }

    /**
     *
     * @return bool
     */
    public function isSeparator(): bool
    {
        return $this->separator;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param MenuItem $menuItem
     * @return MenuItem
     */
    public function addSubMenuItem(MenuItem $menuItem): self
    {
        $this->subMenu[] = $menuItem;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSubMenu(): bool
    {
        return !empty($this->subMenu);
    }

    /**
     * @return MenuItem[]
     */
    public function getSubMenu(): array
    {
        return $this->subMenu;
    }
}
