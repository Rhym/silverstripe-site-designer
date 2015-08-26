<?php

/**
 * Class SiteDesignLeftAndMainExtension
 */
class SiteDesignLeftAndMainExtension extends LeftAndMainExtension
{
    /**
     * @var bool
     */
    private static $enable_menu_item = false;

    public function init()
    {
        /**
         * If the config is set, display the menu item in the LeftAndMain
         */
        if (!$this->owner->config()->enable_menu_item) {
            CMSMenu::remove_menu_item('CMSSiteDesignController');
        }
    }

}