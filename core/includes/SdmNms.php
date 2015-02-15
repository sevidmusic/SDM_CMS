<?php

/**
 * This file defines three distinct classes,
 * two base classes called SdmMenuItem, and
 * SdmMenu, as well as a final class called SdmNav.
 * These objects are dependent on each other, and the
 * order in which the extend each other is important.
 *
 * The SdmMenuItem defines an object
 * that can be used as a single menu item.
 *
 * The SdmMenu extends the SdmMenuItem and can be used
 * to define a single menu object.
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
        $this->menuItemEnabled = (isset($this->menuItemEnabled) ? $this->menuItemEnabled : TRUE);
    }

    public static function generateMenuItem() {
        return new Self;
    }

}

class SdmMenu extends SdmMenuItem {

    // initialize properties needed for a menu
    public $menuId;
    public $menuMachineName;
    public $menuDisplayName;
    public $wrapper;
    public $menuWrappingTagType;
    public $menuPlacement;
    public $menuCssId;
    public $menuCssClasses;
    public $displaypages;
    public $menuKeyholders;
    public $menuItems;

    public function __construct() {
        // unset parent properties
        unset($this->menuItemId);
        unset($this->menuItemMachineName);
        unset($this->menuItemDisplayName);
        unset($this->menuItemWrappingTagType);
        unset($this->menuItemPosition);
        unset($this->menuItemCssId);
        unset($this->menuItemCssClasses);
        unset($this->destinationType);
        unset($this->destination);
        unset($this->arguments);
        unset($this->menuItemKeyholders);
        unset($this->menuItemEnabled);
        // define menu object properties
        $this->menuId = (isset($this->menuId) ? $this->menuId : rand(100000000000, 999999999999));
        $this->menuMachineName = (isset($this->menuMachineName) ? $this->menuMachineName : rand(100000000000, 999999999999));
        $this->menuDisplayName = (isset($this->menuDisplayName) ? $this->menuDisplayName : rand(1000, 9999));
        $this->wrapper = (isset($this->wrapper) ? $this->wrapper : 'main_content');
        $this->menuWrappingTagType = (isset($this->menuWrappingTagType) ? $this->menuWrappingTagType : '');
        $this->menuPlacement = (isset($this->menuPlacement) ? $this->menuPlacement : 'prepend');
        $this->menuCssId = (isset($this->menuCssId) ? $this->menuCssId : $this->menuMachineName);
        $this->menuCssClasses = (isset($this->menuCssClasses) ? $this->menuCssClasses : array('sdm-menu', $this->menuMachineName));
        $this->displaypages = (isset($this->displaypages) ? $this->displaypages : array('all'));
        $this->menuKeyholders = (isset($this->menuKeyholders) ? $this->menuKeyholders : array('root'));
        $this->menuItems = (isset($this->menuItems) ? $this->menuItems : array(SdmMenuItem::generateMenuItem(), SdmMenuItem::generateMenuItem(), SdmMenuItem::generateMenuItem()));
    }

}

/**
 * STILL IN DEV: THIS CLASS IS STILL IN DEVELOPMENT.
 *               COMPLETEING IT IS THE NEXT MAJOR STAGE
 *               IN THE DEVELOPMENT PROCESS OF THE SDM CMS
 *
 * The <b>SdmNms</b> is responsible for provideing the components necessary for
 * navigation management, including CRUD, and security.
 *
 * Menu objects are stored in a JSON file or a DB depending on
 * the configuration of the SDM CMS.
 *
 * @author Sevi Donnelly Foreman
 */
class SdmNms extends SdmCore {

    private static $Initialized;

    public static function sdmInitializeNms() {
        if (!isset(self::$Initialized)) {
            self::$Initialized = new SdmNms;
        }
        return self::$Initialized;
    }

    /**
     * Add a menu to our site.
     * It is suggested that you pass in an instance of the SdmMenu
     * @param $menu mixed Can be an object or an array. If it an array it will be converted internally into an object for processing.
     */
    public function sdmNmsAddMenu($menu) {
        if (gettype($menu) != 'object') {
            $menu = json_decode(json_encode($menu));
        }
        $data = $this->sdmCoreLoadDataObject();
        array_push($data->menus, $menu);
        $json = json_encode($data);
        return file_put_contents($this->getDataDirectoryPath() . '/data.json', $json, LOCK_EX);
    }

    /**
     * update a menu
     */
    public function updateMenu() {

    }

    /**
     * delete a menu
     */
    public function deleteMenu() {

    }

    /**
     * enable a menu
     */
    public function enableMenu() {

    }

    /**
     * disable a menu
     */
    public function disableMenu() {

    }

}

?>
