<?php
// IN DEV
/**
 * This file defines one of three distinct classes,
 * the two base classes called SdmMenuItem, and
 * SdmMenu, as well as a final class called SdmNav.
 * The SdmMenuItem and SdmMenu objects are dependent
 * on each other, and the order in which the extend each
 * other is important.
 *
 * The SdmMenuItem defines an object
 * that can be used as a single menu item.
 *
 * The SdmMenu extends the SdmMenuItem and can be used
 * to define a single menu object, which will contain
 * multiple menuItems.
 *
 * Finally the SdmNav extends SdmCore and provides methods
 * for creating and handling the menu objects.
 *
 */
class SdmMenuItem {

    public $menuItemId;
    public $menuItemMachineName;
    public $menuItemDisplayName;
    public $menuItemWrappingTagType;
    public $menuItemPosition;
    public $menuItemCssId;
    public $menuItemCssClasses;
    public $destinationType;
    public $destination;
    public $arguments;
    public $menuItemKeyholders;
    public $menuItemEnabled;

    public function __construct() {
        $this->menuItemId = (isset($this->menuItemId) ? $this->menuItemId : rand(100000000000, 999999999999));
        $this->menuItemMachineName = (isset($this->menuItemMachineName) ? $this->menuItemMachineName : rand(10000, 99999) . '_' . rand(100, 999) . '_' . rand(1000000, 9999999));
        $this->menuItemDisplayName = (isset($this->menuItemDisplayName) ? $this->menuItemDisplayName : 'S ' . rand(100, 999) . ' D ' . rand(100, 999) . ' M');
        $this->menuItemWrappingTagType = (isset($this->menuItemWrappingTagType) ? $this->menuItemWrappingTagType : '');
        $this->menuItemPosition = (isset($this->menuItemPosition) ? $this->menuItemPosition : rand(-100, 100));
        $this->menuItemCssId = (isset($this->menuItemCssId) ? $this->menuItemCssId : $this->menuItemMachineName);
        $this->menuItemCssClasses = (isset($this->menuItemCssClasses) ? $this->menuItemCssClasses : array('sdm-menu-item', $this->menuItemMachineName));
        $this->destinationType = (isset($this->destinationType) ? $this->destinationType : 'internal');
        $this->destination = (isset($this->destination) ? $this->destination : 'homepage');
        $this->arguments = (isset($this->arguments) ? $this->arguments : array('linkedby' => $this->menuItemMachineName));
        $this->menuItemKeyholders = (isset($this->menuItemKeyholders) ? $this->menuItemKeyholders : array('root'));
        $this->menuItemEnabled = (isset($this->menuItemEnabled) ? $this->menuItemEnabled : true);
    }

    public static function sdmMenuItemGenerateMenuItem() {
        return new Self;
    }

}