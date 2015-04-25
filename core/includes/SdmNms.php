<?php

// IN DEV
/**
 * This file defines three distinct classes,
 * two base classes called SdmMenuItem, and
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
        $this->menuItemEnabled = (isset($this->menuItemEnabled) ? $this->menuItemEnabled : TRUE);
    }

    public static function sdmMenuItemGenerateMenuItem() {
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
        $this->menuItems = (isset($this->menuItems) ? $this->menuItems : array(SdmMenuItem::sdmMenuItemGenerateMenuItem(), SdmMenuItem::sdmMenuItemGenerateMenuItem(), SdmMenuItem::sdmMenuItemGenerateMenuItem()));
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

    public static function sdmNmsInitializeNms() {
        if (!isset(self::$Initialized)) {
            self::$Initialized = new SdmNms;
        }
        return self::$Initialized;
    }

    /**
     * Add a menu to our site.
     * It is suggested that you pass in an instance of the SdmMenu
     * @param $menu mixed .
     */
    public function sdmNmsAddMenu($menu) {
        // we want to make sure we can accsess the new $menu as an object, so if it is not one convert it.
        if (gettype($menu) != 'object') {
            $menu = json_decode(json_encode($menu));
        }
        // load our core data object
        $data = $this->sdmCoreLoadDataObject();
        // load stored menus object from our core data object and convert to an array | makes it easiser to index the new $menu object we are going to be adding
        $menus = json_decode(json_encode($data->menus), TRUE);
        // store the new $menu using it's menu id as it's array index
        $menus[$menu->menuId] = $menu;
        // overwrite existing menus with our new menus array (which WILL contain any menus originally stored)
        $data->menus = $menus;
        // encode $data as json to prep it for storage
        $json = json_encode($data);
        // attempt to write new core $data | if anything fails FALSE will be returned
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $json, LOCK_EX);
    }

    /**
     * update a menu (we seperate add and update so existing menus are not accidently overwritten
     * by calls to sdmNmsAddMenu
     *
     */
    public function sdmNmsupdateMenu() {
        return;
    }

    /**
     * delete a menu
     * @param mixed $menuId <p>Can be a string or an integer whose values matches the id of the menu to be deleted.
     *               i.e., sdmNmsdeleteMenu(1) and sdmNmsdeleteMenu('1') would delete the menu that has a menuId
     *               equal to 1</p>
     * @return bool TRUE if menu was deleted, FALSE on failure.
     */
    public function sdmNmsdeleteMenu($menuId) {
        $data = $this->sdmCoreLoadDataObject();
        unset($data->menus->$menuId);
        $json = json_encode($data);
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $json, LOCK_EX);
    }

    /**
     * enable a menu
     */
    public function sdmNmsenableMenu() {
        return;
    }

    /**
     * disable a menu
     */
    public function sdmNmsdisableMenu() {
        return;
    }

    /**
     * get a stored menu
     * @param mixed An integer or stirng that is equal to the id of the menu we wish to get
     */
    public function sdmNmsGetMenu($menuId) {
        $data = $this->sdmCoreLoadDataObject();
        return $data->menus->$menuId;
    }

    /**
     * Get a single stored menu and return it as an html formatted string
     * @param mixed $menuId An integer or stirng that is equal to the id of the menu we wish to get
     * @return string An html formated string representation of the menu
     */
    public function sdmNmsGetMenuHtml($menuId) {
        $html = '';
        $currentUserRole = 'locked';
        $menu = $this->sdmNmsGetMenu($menuId);
        //$this->sdmCoreSdmReadArray($menu);
        // if menuKeyholders is null assume all users have accsess and show menu || if $currentUserRole exists in menuKeyholders array show menu || if the special all role exists in the menuKeyholders array we assume all user have accsess and show menu
        if ($menu->menuKeyholders === null || in_array($currentUserRole, $menu->menuKeyholders) || in_array('all', $menu->menuKeyholders)) { // we check two things, if the menuKeyholders property is null we assume all users can accsess this menu, if it is not null we check if the users role exists in the menuKeyholders array, we also do a check to see if the 'all' value exists in the menuKeyholders array, if 'all' is present then the menu will be available to all users regardless of the other roles set in menuKeyholders
            $html .= '<h4>' . $menu->menuDisplayName . ' (menuId: ' . $menu->menuId . ')</h4><' . $menu->menuWrappingTagType . ' class="' . (is_array($menu->menuCssClasses) === TRUE ? implode(' ', $menu->menuCssClasses) : str_replace(array(',', '|', ':', ';'), ' ', strval($menu->menuCssClasses))) . '">';
            foreach ($menu->menuItems as $menuItem) {
                switch ($menuItem->destinationType) {
                    case 'internal':
                        $html .= '<' . $menuItem->menuItemWrappingTagType . '><a href="' . $this->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $menuItem->destination . '&' . (is_string($menuItem->arguments) ? str_replace(' ', '', str_replace(array(',', ';', ':', '|'), '&', $menuItem->arguments)) : str_replace(' ', '', implode('&', $menuItem->arguments))) . '">' . $menuItem->menuItemDisplayName . '</a>' . '</' . $menuItem->menuItemWrappingTagType . '>';
                        break;
                    case 'external':
                        $html .= '<' . $menuItem->menuItemWrappingTagType . '>' . '<a href="' . $menuItem->destination . '?&' . (is_string($menuItem->arguments) ? str_replace(' ', '', str_replace(array(',', ';', ':', '|'), '&', $menuItem->arguments)) : str_replace(' ', '', implode('&', $menuItem->arguments))) . '">' . $menuItem->menuItemDisplayName . '</a>' . '</' . $menuItem->menuItemWrappingTagType . '>';
                        break;
                    default:
                        break;
                }
            }
            $html .= '</' . $menu->menuWrappingTagType . '>';
        }
        return ($html !== '' ? $html : FALSE);
    }

    public function sdmNmsGetWrapperMenusHtml() {
        $data = $this->sdmCoreLoadDataObject();
        $html = '';
        foreach ($data->menus as $menu) {
            $html .= $this->sdmNmsGetMenuHtml($menu->menuId);
        }
        return $html;
    }

    /**
     * delete a menu
     */
    public function sdmNmsdeleteMenuItem() {
        return;
    }

    /**
     * enable a menu
     */
    public function sdmNmsupdateMenuItem() {
        return;
    }

    /**
     * disable a menu
     */
    public function sdmNmsdisableMenuItem() {
        return;
    }

    /**
     * get a stored menu
     */
    public function sdmNmsGetMenuItem() {
        return;
    }

}

?>
