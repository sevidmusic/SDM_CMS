<?php

/**
 * Returns an array of integers within the range $s to $e.
 * i.e., rangeArray(4,7) would return the array : array(4 => 4, 5 => 5, 6 => 6, 7 => 7)
 * @param type $s Starting integer
 * @param type $e Ending integer
 * @return array Array of integers within range of $s to $e
 * @todo Incorporate into one of the core classes: SDM NMS, or SDM Core.
 */
function rangeArray($s, $e) {
    $array = array();
    while ($s <= $e) {
        $array[$s] = $s;
        $s++;
    }
    return $array;
}

// create new naviagation management object
$sdmnms = new SdmNms();
// this otpions array will be passed to sdmAssemblerIncorporateAppOutput() wherever this app outputs data.
$options = array(
    'incpages' => array(
        'navigationManagerAddMenuStage1', // select number of menu items
        'navigationManagerAddMenuStage2', // configure menu items
        'navigationManagerAddMenuStage3', // configure menu
        'navigationManagerAddMenuStage4', // add menu
    ),
);
$sdmcore = $sdmcore; // see SdmAssembler.php
if (substr($sdmcore->sdmCoreDetermineRequestedPage(), 0, 17) === 'navigationManager') {
    // CREATE A NEW CONTENT MANAGEMENT OBJECT
    $sdmcms = SdmCms::sdmCmsInitializeCms();
    // determine which section of the content manager was requested
    switch ($sdmcore->sdmCoreDetermineRequestedPage()) {
        // edit content form
        case 'navigationManagerAddMenuStage1': // determine how many menu items this menu will have
            $addMenuFormStage1 = new SdmForm();
            $addMenuFormStage1->form_method = 'post';
            $addMenuFormStage1->form_handler = 'navigationManagerAddMenuStage2';
            $addMenuFormStage1->submitLabel = 'Proceed to Edit Menu Items';
            $addMenuFormStage1->form_elements = array(
                array(
                    'id' => 'number_of_menu_items',
                    'type' => 'select',
                    'element' => 'How manu menu items should this menu have. (you can add or delete menu items later so just pick a number you think will work initially)',
                    'value' => rangeArray(1, 50),
                    'place' => '0',
                ),
                array(
                    'id' => 'menuItem',
                    'type' => 'hidden',
                    'element' => 'Menu Item',
                    'value' => 1, // tracks which menu item is being configured | the first menu item form will always set the properties of the first menu item so we always start with menu item 1
                    'place' => '1',
                ),
            );
            $addMenuFormStage1->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h3>How many menu items will this menu have?</h3>' . $addMenuFormStage1->sdmFormGetForm(), array('incpages' => array('navigationManagerAddMenuStage1')));
            break;
        case 'navigationManagerAddMenuStage2': // configure the menu items
            if (SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName') !== null) {
                $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<div style="border:2px solid #777777;border-radius:9px;padding:20px;height:120px;"><h3>Last Submitted Menu Item:<i style="font-size:.5em;">(DEV NOTE: THIS WILL BE REPLACED BY A PREVIEW OF THE MENU AS IT WOULD LOOK SO FAR BASED ON THE SUBMITTED MENU ITEMS)</i></h3><p>' . SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName') . '</p></div>', array('incmethod' => 'prepend', 'incpages' => $options['incpages']));
            }
            // check to make sure the menuItem number is set, if it doesnt report an error since we cant proceed without it
            switch (SdmForm::sdmFormGetSubmittedFormValue('menuItem') !== null) {
                case TRUE:
                    $addMenuFormStage2 = new SdmForm();
                    $addMenuFormStage2->form_method = 'post';
                    $addMenuFormStage2->form_handler = (SdmForm::sdmFormGetSubmittedFormValue('menuItem') === SdmForm::sdmFormGetSubmittedFormValue('number_of_menu_items') ? 'navigationManagerAddMenuStage3' : 'navigationManagerAddMenuStage2');
                    $addMenuFormStage2->submitLabel = 'Proceed to Edit Menu Settings';
                    $addMenuFormStage2->form_elements = array(
                        array(// store number of menu items so each menu item form can reference it
                            'id' => 'number_of_menu_items',
                            'type' => 'hidden',
                            'element' => 'Number Of Menu Items For This Menu To Hold:',
                            'value' => SdmForm::sdmFormGetSubmittedFormValue('number_of_menu_items'),
                            'place' => '0',
                        ),
                        array(// store and increase menuItem so each form knows what menu item we are configuring | if we are on the last menu item (i.e., post/get value menuItem === number_of_menu_items) then set to null so we form knows to move to the menu configuration form on submission of this menu item
                            'id' => 'menuItem',
                            'type' => 'hidden',
                            'element' => 'Menu Item',
                            'value' => (SdmForm::sdmFormGetSubmittedFormValue('menuItem') === SdmForm::sdmFormGetSubmittedFormValue('number_of_menu_items') ? null : intval(SdmForm::sdmFormGetSubmittedFormValue('menuItem')) + 1), // increase the menuItem value until it is equal to the number_of_menu_items value || If we have cycled through all the menu items set this to null
                            'place' => '1',
                        ),
                        array(
                            'id' => 'menuItemId',
                            'type' => 'hidden',
                            'element' => 'Menu Item Id',
                            'value' => rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999), // set menu item id randomly to insure unique id
                            'place' => '2',
                        ),
                        array(
                            'id' => 'menuItemDisplayName',
                            'type' => 'text',
                            'element' => 'Menu Item Display Name <i style="font-size:.7em;">(The text to show for this menu item.)</i>',
                            'value' => '',
                            'place' => '3',
                        ),
                        array(
                            'id' => 'destinationInternal',
                            'type' => 'select',
                            'element' => 'Destination <i style="font-size:.7em;">(<b>internal</b>: Select a pagename from this menu if this menu item\'s destination type is internal.)</i>',
                            'value' => array_merge($sdmcore->sdmCoreDetermineAvailablePages(), json_decode(json_encode($sdmcore->sdmCoreDetermineEnabledApps()), TRUE)),
                            'place' => '4',
                        ),
                        array(
                            'id' => 'destinationExternal',
                            'type' => 'text',
                            'element' => 'Destination <i style="font-size:.7em;">(<b>internal</b>: Select a pagename from this menu if this menu item\'s destination type is internal.)</i>',
                            'value' => 'homepage',
                            'place' => '5',
                        ),
                        array(
                            'id' => 'destinationType',
                            'type' => 'select',
                            'element' => 'Destination Type <i style="font-size:.7em;">(If destination is a url, choose external, if it is the name of a page that exists on the site choose internal.)</i>',
                            'value' => array('internal' => 'internal', 'external' => 'external'),
                            'place' => '6',
                        ),
                        array(
                            'id' => 'arguments',
                            'type' => 'text',
                            'element' => 'URL Arguments <i style="font-size:.7em;">(Comma seperated list with key=value pairs, i.e., "key1=value1, key2=value2, key3=value3".)</i>',
                            'value' => '',
                            'place' => '7',
                        ),
                        array(
                            'id' => 'menuItemCssId',
                            'type' => 'text',
                            'element' => 'An id to use as the CSS id. <i style="font-size:.7em;">(Should be lowercase using - and _ for spaces, i.e., main_menu or main-menu)</i>',
                            'value' => '',
                            'place' => '8',
                        ),
                        array(
                            'id' => 'menuItemCssClasses',
                            'type' => 'text',
                            'element' => 'Menu Item Css Classes <i style="font-size:.7em;">(Comma seperated class names, i.e., "class1, class-2, class-three, class-four")</i>',
                            'value' => '',
                            'place' => '9',
                        ),
                        array(
                            'id' => 'menuItemEnabled',
                            'type' => 'select',
                            'element' => 'Enabled <i style="font-size:.7em;">(If you choose to disable it this menu item will not be available until you enable it)</i>',
                            'value' => array('enabled' => TRUE, 'disabled' => FALSE),
                            'place' => '10',
                        ),
                        array(
                            'id' => 'menuItemKeyholders',
                            'type' => 'text',
                            'element' => 'Menu Item Keyholders <i style="font-size:.7em;">(Comma seperated list of Roles that can use this menu item, i.e., "root, admin, registered_user")</i>',
                            'value' => '',
                            'place' => '11',
                        ),
                        array(
                            'id' => 'menuItemWrappingTagType',
                            'type' => 'select',
                            'element' => 'Wrapping Tag Type <i style="font-size:.7em;">(The html tag to wrap this menu item with. NOTE:if any menu items use li, all menu items must use li so a list can be created. The form will enforce this.)</i>',
                            'value' => (SdmForm::sdmFormGetSubmittedFormValue('menuItemWrappingTagType') === 'li' ? array('li' => 'li') : array('div' => 'div', 'li' => 'li', 'p' => 'p', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6')),
                            'place' => '12',
                        ),
                        array(
                            'id' => 'menuItemPosition',
                            'type' => 'select',
                            'element' => 'Menu Item Position <i style="font-size:.7em;">(Used to determine position relative to other menu items.)</i>',
                            'value' => rangeArray(1, 50),
                            'place' => '13',
                        ),
                    );
                    if (isset($_POST['SdmForm']['menuItems'])) {
                        // retrieve any menu items already stored in the menuItems array
                        $menuItems = SdmForm::sdmFormGetSubmittedFormValue('menuItems');
                        // create new menu item object using last submitted menu items data
                        $lastSubmittedMenuItem = new SdmMenuItem();
                        $lastSubmittedMenuItem->arguments = explode(',', SdmForm::sdmFormGetSubmittedFormValue('arguments'));
                        $lastSubmittedMenuItem->destination = (SdmForm::sdmFormGetSubmittedFormValue('destinationType') === 'external' ? SdmForm::sdmFormGetSubmittedFormValue('destinationExternal') : SdmForm::sdmFormGetSubmittedFormValue('destinationInternal'));
                        $lastSubmittedMenuItem->destinationType = SdmForm::sdmFormGetSubmittedFormValue('destinationType');
                        $lastSubmittedMenuItem->menuItemCssClasses = explode(',', SdmForm::sdmFormGetSubmittedFormValue('menuItemCssClasses'));
                        $lastSubmittedMenuItem->menuItemCssId = SdmForm::sdmFormGetSubmittedFormValue('menuItemCssId');
                        $lastSubmittedMenuItem->menuItemDisplayName = SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName');
                        $lastSubmittedMenuItem->menuItemEnabled = SdmForm::sdmFormGetSubmittedFormValue('menuItemEnabled');
                        $lastSubmittedMenuItem->menuItemId = SdmForm::sdmFormGetSubmittedFormValue('menuItemId');
                        $lastSubmittedMenuItem->menuItemKeyholders = explode(',', SdmForm::sdmFormGetSubmittedFormValue('menuItemKeyholders'));
                        $lastSubmittedMenuItem->menuItemMachineName = SdmCore::SdmCoreGenerateMachineName(SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName'));
                        $lastSubmittedMenuItem->menuItemPosition = SdmForm::sdmFormGetSubmittedFormValue('menuItemPosition');
                        $lastSubmittedMenuItem->menuItemWrappingTagType = SdmForm::sdmFormGetSubmittedFormValue('menuItemWrappingTagType');
                        // add the last submitted menu item to our menu items array
                        array_push($menuItems, $lastSubmittedMenuItem);
                        // re-create menuItems form element with new menu items stored as its value
                        $mi = array(
                            'id' => 'menuItems',
                            'type' => 'hidden',
                            'element' => 'Menu Items',
                            'value' => $menuItems,
                            'place' => '14',
                        );
                        // add the last submitted menu item to our menu items array
                        array_push($addMenuFormStage2->form_elements, $mi);
                    } else {
                        $menuItems = array();
                        $mi = array(
                            'id' => 'menuItems',
                            'type' => 'hidden',
                            'element' => 'Menu Items',
                            'value' => $menuItems,
                            'place' => '14',
                        );
                        array_push($addMenuFormStage2->form_elements, $mi);
                    }
                    $addMenuFormStage2->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
                    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h3>Configure Menu Items</h3>' . $addMenuFormStage2->sdmFormGetForm(), array('incpages' => array('navigationManagerAddMenuStage2')));
                    break;

                default:
                    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<p>An error occured and the form could not be submitted. Please report this to the site admin. <a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage">Return to the Homepage</a></p>', array('incpages' => array('navigationManagerAddMenuStage2')));
                    break;
            }
            break;
        case 'navigationManagerAddMenuStage3':
            if (SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName') !== null) {
                $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<div style="border:2px solid #777777;border-radius:9px;padding:20px;height:120px;"><h3>Last Submitted Menu Item:<i style="font-size:.5em;">(DEV NOTE: THIS WILL BE REPLACED BY A PREVIEW OF THE MENU AS IT WOULD LOOK SO FAR BASED ON THE SUBMITTED MENU ITEMS)</i></h3><p>' . SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName') . '</p></div>', array('incmethod' => 'prepend', 'incpages' => $options['incpages']));
            }
            // retrieve our menu items
            $menuItems = SdmForm::sdmFormGetSubmittedFormValue('menuItems');
            // since it has not been added to our menu items we create our final menu item object using last submitted menu item form data
            $finalSubmittedMenuItem = new SdmMenuItem();
            $finalSubmittedMenuItem->arguments = explode(',', SdmForm::sdmFormGetSubmittedFormValue('arguments'));
            $finalSubmittedMenuItem->destination = (SdmForm::sdmFormGetSubmittedFormValue('destinationType') === 'external' ? SdmForm::sdmFormGetSubmittedFormValue('destinationExternal') : SdmForm::sdmFormGetSubmittedFormValue('destinationInternal'));
            $finalSubmittedMenuItem->destinationType = SdmForm::sdmFormGetSubmittedFormValue('destinationType');
            $finalSubmittedMenuItem->menuItemCssClasses = explode(',', SdmForm::sdmFormGetSubmittedFormValue('menuItemCssClasses'));
            $finalSubmittedMenuItem->menuItemCssId = SdmForm::sdmFormGetSubmittedFormValue('menuItemCssId');
            $finalSubmittedMenuItem->menuItemDisplayName = SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName');
            $finalSubmittedMenuItem->menuItemEnabled = SdmForm::sdmFormGetSubmittedFormValue('menuItemEnabled');
            $finalSubmittedMenuItem->menuItemId = SdmForm::sdmFormGetSubmittedFormValue('menuItemId');
            $finalSubmittedMenuItem->menuItemKeyholders = explode(',', SdmForm::sdmFormGetSubmittedFormValue('menuItemKeyholders'));
            $finalSubmittedMenuItem->menuItemMachineName = SdmCore::SdmCoreGenerateMachineName(SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName'));
            $finalSubmittedMenuItem->menuItemPosition = SdmForm::sdmFormGetSubmittedFormValue('menuItemPosition');
            $finalSubmittedMenuItem->menuItemWrappingTagType = SdmForm::sdmFormGetSubmittedFormValue('menuItemWrappingTagType');
            // add the last submitted menu item to our menu items array
            array_push($menuItems, $finalSubmittedMenuItem);
            $addMenuFormStage3 = new SdmForm();
            $addMenuFormStage3->form_handler = 'navigationManagerAddMenuStage4';
            $addMenuFormStage3->form_method = 'post';
            $addMenuFormStage3->submitLabel = 'Create Menu';
            $addMenuFormStage3->form_elements = array(
                array(
                    'id' => 'menuItems',
                    'type' => 'hidden',
                    'element' => 'Menu Items',
                    'value' => $menuItems,
                    'place' => '0',
                ),
                array(
                    'id' => 'menuId',
                    'type' => 'hidden',
                    'element' => 'Menu Items',
                    'value' => rand(100000000, 99999999999),
                    'place' => '1',
                ),
                array(
                    'id' => 'menuDisplayName',
                    'type' => 'text',
                    'element' => 'Display Name <i style="font-size:.7em;">(The display name to use for this menu.)</i>',
                    'value' => 'main_content',
                    'place' => '3',
                ),
                array(
                    'id' => 'menuCssId',
                    'type' => 'text',
                    'element' => 'Menu Css Id <i style="font-size:.7em;">(The css id for this menu.)</i>',
                    'value' => 'main_content',
                    'place' => '4',
                ),
                array(
                    'id' => 'menuCssClasses',
                    'type' => 'text',
                    'element' => 'Menu Css Classes<i style="font-size:.7em;">(The css classes for this menu.)</i>',
                    'value' => 'main_content',
                    'place' => '5',
                ),
                array(
                    'id' => 'wrapper',
                    'type' => 'select',
                    'element' => 'Wrapper <i style="font-size:.7em;">(The content wrapper to place the menu in.)</i>',
                    'value' => $sdmcms->sdmCmsDetermineAvailableWrappers(),
                    'place' => '6',
                ),
                array(// @TODO we need to check all menu items for li, we also need to filter set any menu items not set to li to li if any menu item is set to li. This is not urgent.
                    'id' => 'menuWrappingTagType',
                    'type' => 'select',
                    'element' => 'Wrapping Tag Type <i style="font-size:.7em;">(The html tag to wrap this menu item with. NOTE: If li was used for any menu items then ul is enforced so a list can be created.)</i>',
                    'value' => (SdmForm::sdmFormGetSubmittedFormValue('menuItemWrappingTagType') === 'li' ? array('ul' => 'ul') : array('div' => 'div', 'p' => 'p', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6')),
                    'place' => '7',
                ),
                array(
                    'id' => 'menuPlacement',
                    'type' => 'select',
                    'element' => 'Menu Placement <i style="font-size:.7em;">(Determines the placement of the menu in relation to the other content in the content wrapper this menu is being placed in. Prepend will place menu above other content, append will place it below other content.)</i>',
                    'value' => array('prepend' => 'prepend', 'append' => 'append'),
                    'place' => '8',
                ),
                array(
                    'id' => 'displaypages',
                    'type' => 'select',
                    'element' => 'Pages to display menu on<i style="font-size:.7em;">(THIS NEEDS TO BE FIGURED OUT BETTER, POSSIBLY A CHECKLIST OF AVAILABLE PAGES...) FOR NOW all IS THE ONLY OPTION</i>',
                    'value' => array('all' => 'all'),
                    'place' => '9',
                ),
            );
            $addMenuFormStage3->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $addMenuFormStage3->sdmFormGetForm(), array('incpages' => array('navigationManagerAddMenuStage3')));
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h3>Configure Menu</h3>', array('incmethod' => 'prepend', 'incpages' => array('navigationManagerAddMenuStage3')));
            break;
        case 'navigationManagerAddMenuStage4':
            $menu = new SdmMenu();
            $menu->displaypages = SdmForm::sdmFormGetSubmittedFormValue('displaypages'); //
            $menu->menuCssClasses = explode(',', SdmForm::sdmFormGetSubmittedFormValue('menuCssClasses')); // convert commas seperated values into an array of values
            $menu->menuCssId = SdmForm::sdmFormGetSubmittedFormValue('menuCssId'); //
            $menu->menuDisplayName = SdmForm::sdmFormGetSubmittedFormValue('menuDisplayName'); //
            $menu->menuId = SdmForm::sdmFormGetSubmittedFormValue('menuId'); //
            $menu->menuItems = SdmForm::sdmFormGetSubmittedFormValue('menuItems'); //
            $menu->menuKeyholders = SdmForm::sdmFormGetSubmittedFormValue('menuKeyholders');
            $menu->menuMachineName = SdmCore::SdmCoreGenerateMachineName(SdmForm::sdmFormGetSubmittedFormValue('menuDisplayName')); //
            $menu->menuPlacement = SdmForm::sdmFormGetSubmittedFormValue('menuPlacement'); //
            $menu->menuWrappingTagType = SdmForm::sdmFormGetSubmittedFormValue('menuWrappingTagType'); //
            $menu->wrapper = SdmForm::sdmFormGetSubmittedFormValue('wrapper'); //
            $sdmnms->sdmNmsAddMenu($menu);
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<p>Menu Added Successfully (Still in Dev, Does not necessarily indicate succsessful menu add yet)</p>', array('incpages' => array('navigationManagerAddMenuStage4')));
            break;
        default:
            // present content manager menu
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '
                <div id="navigationManager">
                <p>Welcome to the Navigation Manager. Here you can create, edit, delete, and restore content</p>
                    <ul>
                        <li><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=navigationManagerAddMenuStage1">Add Menu</a></li>
                    </ul>
                </div>
                ', array('incpages' => array('navigationManager')));
            break;
    }
}