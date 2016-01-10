<?php

/**
 * Sets up the menu item configuration forms.
 */
// check to make sure the menuItem number is set, if it doesnt report an error since we cant proceed without it
switch (SdmForm::sdmFormGetSubmittedFormValue('menuItem') !== null) {
    case true:
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
                'value' => array_merge($sdmassembler->sdmCoreListAvailablePages(), json_decode(json_encode($sdmassembler->sdmCoreDetermineEnabledApps()), true)),
                'place' => '4',
            ),
            array(
                'id' => 'destinationExternal',
                'type' => 'text',
                'element' => 'Destination <i style="font-size:.7em;">(<b>internal</b>: Select a pagename from this menu if this menu item\'s destination type is internal.)</i>',
                'value' => '',
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
                'value' => array('enabled' => true, 'disabled' => false),
                'place' => '10',
            ),
            array(
                'id' => 'menuItemKeyholders',
                'type' => 'checkbox',
                'element' => 'Menu Item Keyholders <i style="font-size:.7em;">(Check the roles that should have access this menu item)</i>',
                'value' => array('Root' => 'root', 'Basic User' => 'basic_user', 'all' => 'all'),
                'place' => '11',
            ),
            array(
                'id' => 'menuItemWrappingTagType',
                'type' => 'select',
                'element' => 'Wrapping Tag Type <i style="font-size:.7em;">(The html tag to wrap this menu item with. NOTE:if any menu items use li, all menu items must use li so a list can be created. The form will enforce this.)</i>',
                'value' => (SdmForm::sdmFormGetSubmittedFormValue('menuItemWrappingTagType') === 'li' ? array('li' => 'li') : (SdmForm::sdmFormGetSubmittedFormValue('menuItem') > 1 === true ? array('div' => 'div', 'p' => 'p', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6') : array('div' => 'div', 'li' => 'li', 'p' => 'p', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6'))),
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
        // load any menu items already stored and create a menu item from the last submitted menu item configuration form, also display a preview of what the menu looks like based on the menu items submitted so far.
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
            $lastSubmittedMenuItem->menuItemKeyholders = SdmForm::sdmFormGetSubmittedFormValue('menuItemKeyholders');
            $lastSubmittedMenuItem->menuItemMachineName = SdmCore::SdmCoreGenerateMachineName(SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName'));
            $lastSubmittedMenuItem->menuItemPosition = SdmForm::sdmFormGetSubmittedFormValue('menuItemPosition');
            $lastSubmittedMenuItem->menuItemWrappingTagType = SdmForm::sdmFormGetSubmittedFormValue('menuItemWrappingTagType');
            // add the last submitted menu item to our menu items array
            $menuItems[$lastSubmittedMenuItem->menuItemId] = $lastSubmittedMenuItem;
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
            // display of preview of the menu so far
            $sdmassembler->sdmAssemblerIncorporateAppOutput('<div style="border:2px solid #777777;border-radius:9px;padding:20px;height:120px;overflow:auto;"><h3>Last Submitted Menu Item:</h3><p>Display Name: <span style="color:blue;">' . SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName') . '</span> | Destination Type : <span style="color:blue;">' . $lastSubmittedMenuItem->destinationType . '</span> | Destination: <span style="color:blue;">' . $lastSubmittedMenuItem->destination . '</span></p><h3>Menu Preview:</h3>' . $sdmnms->sdmNmsBuildMenuItemsHtml($menuItems) . '</div>', array('incmethod' => 'prepend', 'incpages' => $options['incpages']));
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
        $addMenuFormStage2->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
        $sdmassembler->sdmAssemblerIncorporateAppOutput('<h3>Configure Menu Items</h3>' . $addMenuFormStage2->sdmFormGetForm(), array('incpages' => array('navigationManagerAddMenuStage2')));
        break;

    default:
        $sdmassembler->sdmAssemblerIncorporateAppOutput('<p>An error occured and the form could not be submitted. Please report this to the site admin. <a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage">Return to the Homepage</a></p>', array('incpages' => array('navigationManagerAddMenuStage2')));
        break;
}
