<?php

/**
 * This file defines three distinct classes,
 * two base classes called SdmMenuItem, and
 * SdmMenu, as well as a final class called SdmNav.
 * These objects are dependent on each other, and the
 * order in which the extend each other is important.
 *
 * The SdmMenuItem creates an object
 * that can be used as a single menu item.
 *
 * The SdmMenu extends the SdmMenuItem and can be used
 * as a single menu object.
 *
 * Finally the SdmNav also extends SdmCore and provides methods
 * for handling the menu objects.
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

}

class SdmMenu extends SdmMenuItem {

    // define properties needed for a menu
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
     * add a menu
     * @param $menu mixed Can be an object or an array. If it an array it will be converted internally into an object.
     */
    public function addMenu($menu) {
        $menuObject = json_decode(json_encode($menu));
        $data = $this->sdmCoreLoadDataObject();
        $data->menus->{$menuObject->menuMachineName} = $menu;
        $json = json_encode($data);
        //return $data->menus;
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
