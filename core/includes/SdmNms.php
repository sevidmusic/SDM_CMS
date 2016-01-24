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

/**
 * The <b>SdmNms</b> is responsible for providing the components necessary for
 * navigation management, including CRUD, and security.
 *
 * Menu objects are stored in a JSON file or a DB depending on
 * the configuration of the SDM CMS.
 *
 * @author Sevi Donnelly Foreman
 */
class SdmNms extends SdmGatekeeper
{

    /**
     * Add a menu to our site.
     * @param mixed $menu <p>The new menu. It is preferable to pass in an SdmMenu object,
     * but you can also pass in an array as long as the keys match the property
     * names expected by a SdmMenu object.</p>
     * @return bool true of menu was added, false on failure
     */
    public function sdmNmsAddMenu($menu)
    {
        // we want to make sure we can accsess the new $menu as an object, so if it is not one convert it.
        if (gettype($menu) != 'object') {
            $menu = json_decode(json_encode($menu));
        }
        // load our core data object
        $data = $this->sdmCoreLoadDataObject(false);
        // either load stored menus object from our core data object or if no menus exist yet initilize a default object using stdClass()
        $menus = (isset($data->menus) === true ? $data->menus : new stdClass());
        // store the new $menu in $menus under it's $menu->menuId
        $newMenusId = $menu->menuId;
        $menus->$newMenusId = $menu;
        // overwrite existing menus with our new menus array (which WILL contain any menus originally stored)
        $data->menus = $menus;
        // encode $data as json to prep it for storage
        $json = json_encode($data);
        // attempt to write new core $data | if anything fails false will be returned
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $json, LOCK_EX);
    }

    /**
     * Add a menu item to a menu.
     * @param mixed $menuId <p>The Id of the menu we want to add this menu item to.</p>
     * @param mixed $menuItem <p>The menu item. Preferably passed in the form of an SdmMenuItem object, it is also possible
     * to pass an array as long as the array indexes match the expected property names for a SdmMenuItem object.</p>
     * @return bool true of menu item was added, false on failure
     */
    public function sdmNmsAddMenuItem($menuId, $menuItem)
    {
        // we want to make sure we can accsess the new $menu as an object, so if it is not one convert it.
        if (gettype($menuItem) != 'object') {
            $menuItem = json_decode(json_encode($menuItem));
        }
        // get menu item id
        $menuItemId = $menuItem->menuItemId;
        // load our core data object
        $data = $this->sdmCoreLoadDataObject(false);
        // add menu item to menu
        $data->menus->$menuId->menuItems->$menuItemId = $menuItem;
        // encode $data as json to prep it for storage
        $json = json_encode($data);
        // attempt to write new core $data | if anything fails false will be returned
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $json, LOCK_EX);
    }

    /**
     * Update a menu
     * @param mixed $menuId <p>A string or an integer equal to the menuId
     * of the menu we wish to update.<br>
     * i.e.,<br>
     * <i>sdmNmsUpdateMenu(<b>1234</b>, $menu)</i><br>
     * and<br>
     * <i>sdmNmsUpdateMenu(<b>'1234'</b>, $menu)</i><br>
     * would update the menu with id <b>1234</b></p>
     * @param mixed $menu <p>The new menu. It is preferable to pass in an SdmMenu object,
     * but you can also pass in an array as long as the keys match the property
     * names expected for a SdmMenu object.</p>
     * @return bool true of menu was added, false on failure
     */
    public function sdmNmsUpdateMenu($menuId, $menu)
    {
        // we want to make sure we can accsess the new $menu as an object, so if it is not one convert it.
        if (gettype($menu) != 'object') {
            $menu = json_decode(json_encode($menu));
        }
        // load our core data object
        $data = $this->sdmCoreLoadDataObject(false);
        // load stored menus object from our core data object
        $menus = json_decode(json_encode($data->menus));
        // update menu with menuId === $menuId
        unset($menus->menuId);
        $menus->$menuId = $menu;
        // update menus object to reflect changes
        $data->menus = $menus;
        // encode $data as json to prep it for storage
        $json = json_encode($data);
        // attempt to write new core $data | if anything fails false will be returned
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $json, LOCK_EX);
    }

    /**
     * Update a menu
     * @param mixed $menuId <p>A string or an integer equal to the menuId
     * of the menu we wish to update.<br>
     * i.e.,<br>
     * <i>sdmNmsUpdateMenu(<b>1234</b>, $menu)</i><br>
     * and<br>
     * <i>sdmNmsUpdateMenu(<b>'1234'</b>, $menu)</i><br>
     * would update the menu with id <b>1234</b></p>
     * @param mixed $menu <p>The new menu. It is preferable to pass in an SdmMenu object,
     * but you can also pass in an array as long as the keys match the property
     * names expected for a SdmMenu object.</p>
     * @return bool true of menu was added, false on failure
     */
    public function sdmNmsUpdateMenuItem($menuId, $menuItemId, $menuItem)
    {
        // load our core data object
        $data = $this->sdmCoreLoadDataObject(false);
        // load stored menus object from our core data object
        $menus = $data->menus;
        // get the menu this menu item belongs to
        $menu = $menus->$menuId;
        // update menu item
        unset($menu->menuItems->$menuItemId);
        $menu->menuItems->$menuItemId = $menuItem;
        // update menus object to reflect changes to the menu that the updated menu item belongs to.
        unset($menus->$menuId);
        $menus->$menuId = $menu;
        $data->menus = $menus;
        // encode $data as json to prep it for storage
        $json = json_encode($data);
        // attempt to write new core $data | if anything fails false will be returned
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $json, LOCK_EX);
    }

    /**
     * <p>Deletes a menu</p>
     * @param mixed $menuId <p>Can be a string or an integer whose value matches the id of the menu to be deleted.
     *               i.e., sdmNmsDeleteMenu(1) and sdmNmsDeleteMenu('1') would delete the menu that has a menuId
     *               equal to 1</p>
     * @return mixed <p>If menu was deleted then the display name of the deleted menu is returned, if menu could not be
     * deleted then the boolean false is returned.</p>
     */
    public function sdmNmsDeleteMenu($menuId)
    {
        $data = $this->sdmCoreLoadDataObject(false);
        $menuDisplayName = $data->menus->$menuId->menuDisplayName;
        unset($data->menus->$menuId);
        $json = json_encode($data);
        $status = file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $json, LOCK_EX);
        return ($status === false ? false : $menuDisplayName);
    }

    /**
     * <p>Deltes a menu item belonging to the menu with $menuId</p>
     * @param mixed $menuId <p>Can be a string or an integer whose value matches the id of the menu the menu item belongs to.</p>
     * @param mixed $menuItemId <p>Can be a string or an integer whose value matches the id of the menu item to be deleted.</p>
     * @return mixed <p>If menu item was deleted then the display name of the deleted menu item is returned, if menu item could not be
     * deleted then the boolean false is returned.</p>     */
    public function sdmNmsDeleteMenuItem($menuId, $menuItemId)
    {
        $data = $this->sdmCoreLoadDataObject(false);
        $menuItemDisplayName = $data->menus->$menuId->menuItems->$menuItemId->menuItemDisplayName;
        unset($data->menus->$menuId->menuItems->$menuItemId);
        $json = json_encode($data);
        $status = file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $json, LOCK_EX);
        return ($status === false ? false : $menuItemDisplayName);
    }

    /**
     * <p>Gets the html needed to display the wrappers menus and incorporates it into the page</p>
     * @param string $wrapper <p>The wrapper to get menus for</p>
     * @param string $wrapperAssembledContent <p>The wrapper html as it is assembled so far,
     * the menu incorporation happens last in order to allow apps to modify menus prior to
     * incorporating them into the page</p>
     * @return string <p>The html for the menus.</p>
     */
    public function sdmNmsGetWrapperMenusHtml($wrapper, $wrapperAssembledContent)
    {
        $prepend = '';
        $append = '';
        if (isset($this->DataObject->menus)) {
            foreach ($this->DataObject->menus as $menu) {
                if ($menu->wrapper === $wrapper) {
                    switch ($menu->menuPlacement) {
                        case 'prepend':
                            $prepend .= $this->sdmNmsGetMenuHtml($menu->menuId);
                            break;
                        default:
                            $append .= $this->sdmNmsGetMenuHtml($menu->menuId);
                            break;
                    }
                }
            }
        }
        return $prepend . $wrapperAssembledContent . $append;
    }

    /**
     * Get a single stored menu and return it as an html formatted string
     * @param mixed $menuId An integer or stirng that is equal to the id of the menu we wish to get
     * @return string An html formated string representation of the menu
     */
    public function sdmNmsGetMenuHtml($menuId)
    {
        $currentUserRole = (SdmGatekeeper::sdmGatekeeperAuthenticate() === true ? 'root' : 'basic_user'); // this is a dev role, the users role should be determined by the Sdm Gatekeeper once it is built
        $menu = $this->sdmNmsGetMenu($menuId);
        // @todo : there is no need to instatiate SdmCore() here since this class is a child of SdmCore().
        // if $currentUserRole exists in menuKeyholders array show menu || if the special role "all" exists in the menuKeyholders array we assume all users have accsess and show menu || we no longer  assume that all users have accsess to this menu if menuKeyholders is null
        if (in_array($currentUserRole, $menu->menuKeyholders) || in_array('all', $menu->menuKeyholders)) { // we check three things, if the menuKeyholders property is null we assume all users can accsess this menu, if it is not null we check if the users role exists in the menuKeyholders array, we also do a check to see if the 'all' value exists in the menuKeyholders array, if 'all' is present then the menu will be available to all users regardless of the other roles set in menuKeyholders
            $html = (in_array($this->sdmCoreDetermineRequestedPage(), $menu->displaypages) === true || in_array('all', $menu->displaypages) === true ? $this->sdmNmsBuildMenuHtml($menu) : '<!-- Menu "' . $menu->menuDisplayName . '" Placeholder -->'); //$this->sdmNmsBuildMenuHtml($menu);
        }
        return (isset($html) && $html !== '' ? $html : false);
    }

    /**
     * get a stored menu
     * @param mixed $menuId <p>An integer or stirng that is equal to the id of the menu we wish to get</p>
     * @return object <p>The menu with id $menuId</p>
     */
    public function sdmNmsGetMenu($menuId)
    {
        //$data = $this->sdmCoreLoadDataObject(false);
        return $this->DataObject->menus->$menuId;
    }

    /**
     * <p>Builds the html for a menu object.</p>
     * @param object $menu <p>The menu to object to build from.</p>
     * @return string <p>The Menu's html</p>
     */
    public function sdmNmsBuildMenuHtml($menu)
    {
        $html = '<!-- MENU: ' . $menu->menuDisplayName . ' | MENUID: ' . $menu->menuId . ' | MENU MACHINE NAME: ' . $menu->menuMachineName . ' --><' . $menu->menuWrappingTagType . ' id="' . $menu->menuCssId . '" class="' . (is_array($menu->menuCssClasses) === true ? implode(' ', $menu->menuCssClasses) : str_replace(array(',', '|', ':', ';'), ' ', strval($menu->menuCssClasses))) . '">';
        $html .= $this->sdmNmsBuildMenuItemsHtml($menu->menuItems, $menu->menuId);
        $html .= '</' . $menu->menuWrappingTagType . '>';
        return $html;
    }

    /**
     * <p>Builds the html for a menu object's menu items.</p>
     * @param array $menuItems <p>The menu's menu items array (e.g., $menu->menuItems).</p>
     * @param string $menuId <p>Id of the menu these menu items belong to. Defaults to 'unknown', this allows
     * this method to be called to build the html for a set of menu items that do not necessarily
     * belong to a menu, for example this happens in the Navigation Manager when a menu is being created since
     * the menu items are configured before the menu itself.</p>
     * @return string <p>The Menu Item's html</p>
     */
    public function sdmNmsBuildMenuItemsHtml($menuItems, $menuId = 'unknown')
    {
        $html = '';
        $currentUserRole = (SdmGatekeeper::sdmGatekeeperAuthenticate() === true ? 'root' : 'basic_user'); // this is a dev role, the users role should be determined by the Sdm Gatekeeper once it is built
        $orderedMenuItems = array();
        $usedPositions = array();
        // order menu items by menu item position | first build an array of ordered menu items where each menu item is indexed by it's menuItemPosition
        foreach ($menuItems as $menuItem) {
            // if menu item position is already in the $usedPositions array add 1 to avoid overwriting previous menu items
            $position = (in_array($menuItem->menuItemPosition, $usedPositions) === true ? $menuItem->menuItemPosition + 1 : $menuItem->menuItemPosition);
            // index our menu item by $position in the new $orderedMenuItems array | $position and $usedPositions will insure menu items do not get indexed by a position that was already assigned as an index.
            $orderedMenuItems[$position] = $menuItem;
            // store the $position in the $usedPositions array so we can keep track of positions already in use, this allows us to prevent menu items that share the same $menuItem->menuItemPosition from overwriting each other by assuring no menu item is indexed by a $position that was already used.
            $usedPositions[] = $position;
        }
        // use ksort() to order the menu items by their new indexes, the indexes will reflect the integer assigend to the menu item's menuItemPosition property
        ksort($orderedMenuItems, SORT_NUMERIC);
        // build each ordered menu itmes html
        foreach ($orderedMenuItems as $orderedMenuItem) {
            if (in_array($currentUserRole, $orderedMenuItem->menuItemKeyholders) === true || in_array('all', $orderedMenuItem->menuItemKeyholders)) {
                if ($orderedMenuItem->menuItemEnabled === true || $orderedMenuItem->menuItemEnabled === '1' || $orderedMenuItem->menuItemEnabled === 1) {
                    switch ($orderedMenuItem->destinationType) {
                        case 'internal':
                            $html .= '<' . $orderedMenuItem->menuItemWrappingTagType . ' id="' . $orderedMenuItem->menuItemCssId . '" class="' . implode(' ', $orderedMenuItem->menuItemCssClasses) . '"><a href="' . $this->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $orderedMenuItem->destination . '&linkedByMenu=' . $menuId . '&linkedByMenuItem=' . $orderedMenuItem->menuItemId . (isset($orderedMenuItem->arguments) === true && !empty($orderedMenuItem->arguments) && $orderedMenuItem->arguments[0] != '' ? '&' : '') . (is_string($orderedMenuItem->arguments) ? str_replace(' ', '', str_replace(array(',', ';', ':', '|'), '&', $orderedMenuItem->arguments)) : str_replace(' ', '', implode('&', $orderedMenuItem->arguments))) . '">' . $orderedMenuItem->menuItemDisplayName . '</a>' . '</' . $orderedMenuItem->menuItemWrappingTagType . '>';
                            break;
                        case 'external': // $orderedMenuItem->destination
                            $html .= '<' . $orderedMenuItem->menuItemWrappingTagType . ' id="' . $orderedMenuItem->menuItemCssId . '" class="' . implode(' ', $orderedMenuItem->menuItemCssClasses) . '"><a href="' . $orderedMenuItem->destination . (isset($orderedMenuItem->arguments) === true && !empty($orderedMenuItem->arguments) && $orderedMenuItem->arguments[0] != '' ? '?&' : '') . (is_string($orderedMenuItem->arguments) ? str_replace(' ', '', str_replace(array(',', ';', ':', '|'), '&', $orderedMenuItem->arguments)) : str_replace(' ', '', implode('&', $orderedMenuItem->arguments))) . '" target="_blank">' . $orderedMenuItem->menuItemDisplayName . '</a>' . '</' . $orderedMenuItem->menuItemWrappingTagType . '>';
                            break;
                        default:
                            break;
                    }
                } else { // menu item is disabled
                    $html .= '<!-- Disabled Menu Item "' . $orderedMenuItem->menuItemDisplayName . '" Placeholder -->';
                }
            } else { // user does not have permission to see this menu item
                $html .= '<!-- Menu Item "' . $orderedMenuItem->menuItemDisplayName . '" Placeholder -->';
            }
        }
        return $html;
    }

    /**
     * get a stored menu item
     * @param mixed $menuId <p>An integer or stirng that is equal to the id of the menu the menu item belongs to</p>
     * @param mixed $menuItemId <p>An integer or stirng that is equal to the id of the menu item we wish to get</p>
     * @return object <p>The menu item with id $menuItemId that belongs to the menu with id $menuId</p>
     */
    public function sdmNmsGetMenuItem($menuId, $menuItemId)
    {
        return $this->DataObject->menus->$menuId->menuItems->$menuItemId;
    }

    /**
     * <p>Generates an array of menu properties for all available menus where $propKey is
     * the property to use for indexing and $propValue is the property to asign as a value.</p>
     * <p>e.g.<br><b>sdmNmsGenerateMenuPropertiesArray(<i>'menuId', 'menuMachineName'</i>)</b>
     * would generate an array with the following structure:<br>
     * <i>array(<br><b>$menu1</b>-><b>menuId</b> => <b>$menu1</b>-><b>menuMachineName</b>,<br><b>$menu2</b>-><b>menuId</b> => <b>$menu2</b>-><b>menuMachineName</b>,<br>etc...,<br>);</p>
     * @param string $propKey <p>The name of the property to use for indexes.</p>
     * @param string $propValue <p>The name of the property to use for values.</p>
     * @return array <p>An array of all available menus indexed by menu->$propKey with values set to menu->$propValue</p>
     */
    public function sdmNmsGenerateMenuPropertiesArray($propKey, $propValue)
    {
        $menus = $this->sdmCoreLoadDataObject(false)->menus;
        foreach ($menus as $menu) {
            $availableMenus[$menu->$propKey] = $menu->$propValue;
        }
        return $availableMenus;
    }

    /**
     * <p>Returns an array of menu ids for all the stored menus.</p>
     * @return type <p>An array of menu ids for all available menus.</p>
     */
    public function sdmNmsGetMenuIds()
    {
        return array_keys(json_decode(json_encode($this->sdmCoreLoadDataObject(false)->menus), true));
    }

    /**
     * <p>Returns an array of menu item ids belonging to the menu.</p>
     * @param string <p>The menu id for the menu we want to get menu item ids from</p>
     * @return type <p>An array of menu item ids for all menu items beloning to the menu.</p>
     */
    public function sdmNmsGetMenuItemIds($menuId)
    {
        $menu = $this->sdmCoreLoadDataObject(false)->menus->$menuId;
        return array_keys(json_decode(json_encode($menu->menuItems), true));
    }

}
