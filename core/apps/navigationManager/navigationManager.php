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
// this otpions array will be passed to incorporateAppOutput() wherever this app outputs data.
$options = array(
    'incpages' => array(
        'navigationManagerAddMenuStage1', // select number of menu items
        'navigationManagerAddMenuStage2', // configure menu items
        'navigationManagerAddMenuStage3', // configure menu
        'navigationManagerAddMenuStage4', // add menu
    ),
);
$sdmcore = $sdmcore; // see SdmAssembler.php
if (substr($sdmcore->determineRequestedPage(), 0, 17) === 'navigationManager') {
    // CREATE A NEW CONTENT MANAGEMENT OBJECT
    $sdmcms = SdmCms::sdmInitializeCms();
    // determine which section of the content manager was requested
    switch ($sdmcore->determineRequestedPage()) {
        // edit content form
        case 'navigationManagerAddMenuStage1': // determine how many meni items this menu will have
            $addMenuFormStage1 = new SDM_Form();
            $addMenuFormStage1->form_method = 'post';
            $addMenuFormStage1->form_handler = 'navigationManagerAddMenuStage2';
            $addMenuFormStage1->submitLabel = 'Proceed to Edit Menu Items';
            $addMenuFormStage1->form_elements = array(
                array(
                    'id' => 'number_of_menu_items',
                    'type' => 'select',
                    'element' => 'How manu menu items should this menu have. (you can add or delete menu items later so just pick a number you think will work initially)',
                    'value' => rangeArray(1, 50),
                    'place' => '1',
                ),
                array(
                    'id' => 'menuItem',
                    'type' => 'hidden',
                    'element' => 'Menu Item',
                    'value' => 1, // the first menu item form will always set the properties of the first menu item
                    'place' => '0',
                ),
            );
            $addMenuFormStage1->__build_form($sdmcore->getRootDirectoryUrl());
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h3>How many menu items will this menu have?</h3>' . $addMenuFormStage1->__get_form(), array('incpages' => array('navigationManagerAddMenuStage1')));
            break;
        case 'navigationManagerAddMenuStage2': // configure the menu items
            // check to make sure the menuItem number is set, if it doesnt report an error since we cant proceed without it
            switch (SDM_Form::get_submitted_form_value('menuItem') !== null) {
                case TRUE:
                    $addMenuFormStage2 = new SDM_Form();
                    $addMenuFormStage2->form_method = 'post';
                    $addMenuFormStage2->form_handler = (SDM_Form::get_submitted_form_value('menuItem') === SDM_Form::get_submitted_form_value('number_of_menu_items') ? 'navigationManagerAddMenuStage3' : 'navigationManagerAddMenuStage2');
                    $addMenuFormStage2->submitLabel = 'Proceed to Edit Menu Settings';
                    $addMenuFormStage2->form_elements = array(
                        array(// store number of menu items so each menu item form can reference it
                            'id' => 'number_of_menu_items',
                            'type' => 'hidden',
                            'element' => 'Number Of Menu Items',
                            'value' => SDM_Form::get_submitted_form_value('number_of_menu_items'),
                            'place' => '1000',
                        ),
                        array(// store and increase menuItem so each form knows what menu item we are configuring
                            'id' => 'menuItem',
                            'type' => 'hidden',
                            'element' => 'Menu Item',
                            'value' => (SDM_Form::get_submitted_form_value('menuItem') === SDM_Form::get_submitted_form_value('number_of_menu_items') ? null : intval(SDM_Form::get_submitted_form_value('menuItem')) + 1), // increase the menuItem value until it is equal to the number_of_menu_items value || If we have cycled through all the menu items set this to null
                            'place' => '100',
                        ),
                        array(
                            'id' => 'arguments',
                            'type' => 'hidden',
                            'element' => 'URL Arguments',
                            'value' => array('dev' => 'NMS'),
                            'place' => '0',
                        ),
                        array(
                            'id' => 'menuItemId',
                            'type' => 'hidden',
                            'element' => 'Menu Item Id',
                            'value' => rand(1000000000, 9999999999),
                            'place' => '0',
                        ),
                        array(
                            'id' => 'destination',
                            'type' => 'text',
                            'element' => 'Destination (a page name or a url)',
                            'value' => 'homepage', // default to homepage so no broken links are added to our menu system
                            'place' => '1',
                        ),
                        array(
                            'id' => 'destinationType',
                            'type' => 'hidden',
                            'element' => 'Destination Type',
                            'value' => 'internal',
                            'place' => '2',
                        ),
                        array(
                            'id' => 'menuItemCssClasses',
                            'type' => 'hidden',
                            'element' => 'Menu Item Css Classes',
                            'value' => array('dev', 'NMS'),
                            'place' => '3',
                        ),
                        array(
                            'id' => 'menuItemCssId',
                            'type' => 'text',
                            'element' => 'An id to use as the CSS id',
                            'value' => 'dev-NMS',
                            'place' => '4',
                        ),
                        array(
                            'id' => 'menuItemDisplayName',
                            'type' => 'text',
                            'element' => 'Menu Item Display Name (will be the text shown for the link)',
                            'value' => rand(1000, 9999),
                            'place' => '5',
                        ),
                        array(
                            'id' => 'menuItemEnabled',
                            'type' => 'hidden',
                            'element' => 'Enabled',
                            'value' => TRUE,
                            'place' => '6',
                        ),
                        array(
                            'id' => 'menuItemKeyholders',
                            'type' => 'hidden',
                            'element' => 'Menu Item Keyholders',
                            'value' => array('root'),
                            'place' => '7',
                        ),
                        array(
                            'id' => 'menuItemMachineName',
                            'type' => 'hidden',
                            'element' => 'Menu Item Machine Name',
                            'value' => rand(10000, 99999),
                            'place' => '8',
                        ),
                        array(
                            'id' => 'menuItemPosition',
                            'type' => 'select',
                            'element' => 'Menu Item Position',
                            'value' => rangeArray(1, 50),
                            'place' => '8',
                        ),
                        array(
                            'id' => 'menuItemWrappingTagType',
                            'type' => 'text',
                            'element' => 'Wrapping Tag Type',
                            'value' => rand(10000, 99999),
                            'place' => '8',
                        ),
                    );
                    if (isset($_POST['sdm_form']['menuItems'])) {
                        // retrieve any menu items already stored in the menuItems array
                        $menuItems = SDM_Form::get_submitted_form_value('menuItems');
                        // create new menu item object using last submitted menu items data
                        $lastSubmittedMenuItem = new SdmMenuItem();
                        $lastSubmittedMenuItem->arguments = array();
                        $lastSubmittedMenuItem->destination = SDM_Form::get_submitted_form_value('destination');
                        $lastSubmittedMenuItem->destinationType = SDM_Form::get_submitted_form_value('destinationType');
                        $lastSubmittedMenuItem->menuItemCssClasses = SDM_Form::get_submitted_form_value('menuItemCssClasses');
                        $lastSubmittedMenuItem->menuItemCssId = SDM_Form::get_submitted_form_value('menuItemCssId');
                        $lastSubmittedMenuItem->menuItemDisplayName = SDM_Form::get_submitted_form_value('menuItemDisplayName');
                        $lastSubmittedMenuItem->menuItemEnabled = SDM_Form::get_submitted_form_value('menuItemEnabled');
                        $lastSubmittedMenuItem->menuItemId = SDM_Form::get_submitted_form_value('menuItemId');
                        $lastSubmittedMenuItem->menuItemKeyholders = SDM_Form::get_submitted_form_value('menuItemKeyholders');
                        $lastSubmittedMenuItem->menuItemMachineName = SDM_Form::get_submitted_form_value('menuItemMachineName');
                        $lastSubmittedMenuItem->menuItemPosition = rand(-100, 100);
                        $lastSubmittedMenuItem->menuItemWrappingTagType = 'div';
                        // add the last submitted menu item to our menu items array
                        array_push($menuItems, $lastSubmittedMenuItem);
                        // re-create menuItems form element with new menu items stored as its value
                        $mi = array(
                            'id' => 'menuItems',
                            'type' => 'hidden',
                            'element' => 'Menu Items',
                            'value' => $menuItems,
                            'place' => '8',
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
                            'place' => '8',
                        );
                        array_push($addMenuFormStage2->form_elements, $mi);
                    }
                    $addMenuFormStage2->__build_form($sdmcore->getRootDirectoryUrl());
                    $sdmcore->sdm_read_array(array('menuItem' => SDM_Form::get_submitted_form_value('menuItem'), 'number_of_menu_items' => SDM_Form::get_submitted_form_value('number_of_menu_items'), 'number of stored menu items' => count($menuItems), 'menuItems' => $menuItems));
                    $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h3>Configure Menu Items</h3>' . $addMenuFormStage2->__get_form(), array('incpages' => array('navigationManagerAddMenuStage2')));
                    break;

                default:
                    $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<p>An error occured and the form could not be submitted. Please report this to the site admin. <a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=homepage">Return to the Homepage</a></p>', array('incpages' => array('navigationManagerAddMenuStage2')));
                    break;
            }
            break;
        case 'navigationManagerAddMenuStage3':
            // retrieve our menu items
            $menuItems = SDM_Form::get_submitted_form_value('menuItems');
            // since it has not been added to our menu items we create our final menu item object using last submitted menu item form data
            $finalSubmittedMenuItem = new SdmMenuItem();
            $finalSubmittedMenuItem->arguments = array();
            $finalSubmittedMenuItem->destination = SDM_Form::get_submitted_form_value('destination');
            $finalSubmittedMenuItem->destinationType = 'internal';
            $finalSubmittedMenuItem->menuItemCssClasses = array();
            $finalSubmittedMenuItem->menuItemCssId = rand(10000, 99999);
            $finalSubmittedMenuItem->menuItemDisplayName = SDM_Form::get_submitted_form_value('menuItemDisplayName');
            $finalSubmittedMenuItem->menuItemEnabled = TRUE;
            $finalSubmittedMenuItem->menuItemId = rand(100000000000, 999999999999);
            $finalSubmittedMenuItem->menuItemKeyholders = array();
            $finalSubmittedMenuItem->menuItemMachineName = rand(1000, 9999) . '_mi_' . rand(10000, 99999) . 'sdm';
            $finalSubmittedMenuItem->menuItemPosition = rand(-100, 100);
            $finalSubmittedMenuItem->menuItemWrappingTagType = 'div';
            // add the last submitted menu item to our menu items array
            array_push($menuItems, $finalSubmittedMenuItem);
            $addMenuFormStage3 = new SDM_Form();
            $addMenuFormStage3->form_handler = 'navigationManagerAddMenuStage4';
            $addMenuFormStage3->form_method = 'post';
            $addMenuFormStage3->submitLabel = 'Create Menu';
            $addMenuFormStage3->form_elements = array(
                array(
                    'id' => 'formElements',
                    'type' => 'hidden',
                    'element' => 'Form Elements',
                    'value' => rand(10000, 99999),
                    'place' => '8',
                ),
            );
            $addMenuFormStage3->__build_form($sdmcore->getRootDirectoryUrl());
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $addMenuFormStage3->__get_form(), array('incpages' => array('navigationManagerAddMenuStage3')));
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h3>Configure Menu</h3>', array('incpages' => array('navigationManagerAddMenuStage3')));
            $sdmcore->sdm_read_array(array('menuItem' => SDM_Form::get_submitted_form_value('menuItem'), 'number_of_menu_items' => SDM_Form::get_submitted_form_value('number_of_menu_items'), 'number of stored menu items' => count($menuItems), 'menuItems' => $menuItems));
            break;
        case 'navigationManagerAddMenuStage4':
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<p>Menu Added Successfully (Still in Dev, Does not necessarily indicate succsessful menu add yet)</p>', array('incpages' => array('navigationManagerAddMenuStage4')));
            break;
        default:
            // present content manager menu
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '
                <div id="navigationManager">
                <p>Welcome to the Navigation Manager. Here you can create, edit, delete, and restore content</p>
                    <ul>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManagerAddMenuStage1">Add Menu</a></li>
                    </ul>
                </div>
                ', array('incpages' => array('navigationManager')));
            break;
    }
}

/*
              // create a few menu items
              $newMenuItem = new SdmMenuItem();
              $newMenuItem->arguments = array('source' => 'naviagtionManager_testMenuItem1');
              $newMenuItem->destination = 'contentManager';
              $newMenuItem->destinationType = 'internal'; // should be determined internally | i.e., if http::// or www. is the start of the string set to external, otehrwise treat as internal
              $newMenuItem->menuItemCssClasses = array('navigationManager', 'testMenuItem');
              $newMenuItem->menuItemCssId = 'menuItem1';
              $newMenuItem->menuItemDisplayName = 'Content Manager';
              $newMenuItem->menuItemEnabled;
              //$newMenuItem->menuItemId; set internaly
              $newMenuItem->menuItemKeyholders = array('root');
              $newMenuItem->menuItemMachineName = 'menuItem1';
              $newMenuItem->menuItemPosition = 0;
              $newMenuItem->menuItemWrappingTagType = 'p';
             */
            // create menu
//            $menu = new SdmMenu();
//            $menu->displaypages = array('homepage');
//            $menu->menuCssClasses = array('navigationManager', 'testMenu');
//            $menu->menuCssId = 'testMenu';
//            $menu->menuDisplayName = 'Test Menu';
//            //$menu->menuId; // set internally
//            $menu->menuItems = array($newMenuItem);
//            $menu->menuKeyholders = array('root');
//            $menu->menuMachineName = 'testMenu';
//            $menu->menuPlacement = 'prepend';
//            $menu->menuWrappingTagType = 'div';
//            $menu->wrapper = 'main_content';
            //$sdmnms->sdmNmsAddMenu($menu);