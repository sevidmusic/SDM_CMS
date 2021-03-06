<?php

$menu = $sdmassembler->sdmNmsGetMenu($_GET['menuId']);
// get menu item ids, used to find the first menu item by id
$menuItemIds = $sdmassembler->sdmNmsGetMenuItemIds($menu->menuId);
// get first menu item by id to use as a reference to determine what wrapping tag types should be available to the menu and other menu items
$firstMenuItem = $menu->menuItems->$menuItemIds[0];
// get first menu item's wrapping tag type, this is used to determine which tag types should be available to the form element menuWrappingTagType
$firstMenuItemWrappingTagType = $firstMenuItem->menuItemWrappingTagType;
// get the menu item we want to edit
$menuItem = $sdmassembler->sdmNmsGetMenuItem($_GET['menuId'], $_GET['menuItemId']);
$editMenuItemForm = new SdmForm();
$editMenuItemForm->form_method = 'post';
$editMenuItemForm->formHandler = 'navigationManagerEditMenuStage3_submitmenuitemchanges';
$editMenuItemForm->submitLabel = 'Proceed to Edit Menu Settings';
$editMenuItemForm->formElements = array(
    array(
        'id' => 'menuId',
        'type' => 'hidden',
        'element' => 'Menu Id',
        'value' => $menu->menuId,
        'place' => '1',
    ),
    array(
        'id' => 'menuItemId',
        'type' => 'hidden',
        'element' => 'Menu Item Id',
        'value' => $menuItem->menuItemId,
        'place' => '2',
    ),
    array(
        'id' => 'menuItemDisplayName',
        'type' => 'text',
        'element' => 'Menu Item Display Name <i style="font-size:.7em;">(The text to show for this menu item.)</i>',
        'value' => $menuItem->menuItemDisplayName,
        'place' => '3',
    ),
    array(
        'id' => 'destinationInternal',
        'type' => 'select',
        'element' => 'Destination <i style="font-size:.7em;">(<b>internal</b>: Select a pagename from this menu if this menu item\'s destination type is internal.)</i>',
        'value' => SdmForm::sdmFormSetDefaultInputValues(array_merge($sdmassembler->sdmCoreDetermineAvailablePages(), json_decode(json_encode($sdmassembler->sdmCoreDetermineEnabledApps()), true)), $menuItem->destination),
        'place' => '4',
    ),
    array(
        'id' => 'destinationExternal',
        'type' => 'text',
        'element' => 'Destination <i style="font-size:.7em;">(<b>external</b>: Enter the url this menu item should point to. e.g., http://www.example.com</i>',
        'value' => ($menuItem->destinationType === 'external' ? $menuItem->destination : ''),
        'place' => '5',
    ),
    array(
        'id' => 'destinationType',
        'type' => 'select',
        'element' => 'Destination Type <i style="font-size:.7em;">(If destination is a url, choose external, if it is the name of a page that exists on the site choose internal.)</i>',
        'value' => SdmForm::sdmFormSetDefaultInputValues(array('internal' => 'internal', 'external' => 'external'), $menuItem->destinationType),
        'place' => '6',
    ),
    array(
        'id' => 'arguments',
        'type' => 'text',
        'element' => 'URL Arguments <i style="font-size:.7em;">(Comma seperated list with key=value pairs, i.e., "key1=value1, key2=value2, key3=value3".)</i>',
        'value' => implode(',', $menuItem->arguments),
        'place' => '7',
    ),
    array(
        'id' => 'menuItemCssId',
        'type' => 'text',
        'element' => 'An id to use as the CSS id. <i style="font-size:.7em;">(Should be lowercase using - and _ for spaces, i.e., main_menu or main-menu)</i>',
        'value' => $menuItem->menuItemCssId,
        'place' => '8',
    ),
    array(
        'id' => 'menuItemCssClasses',
        'type' => 'text',
        'element' => 'Menu Item Css Classes <i style="font-size:.7em;">(Comma seperated class names, i.e., "class1, class-2, class-three, class-four")</i>',
        'value' => implode(',', $menuItem->menuItemCssClasses),
        'place' => '9',
    ),
    array(
        'id' => 'menuItemEnabled',
        'type' => 'select',
        'element' => 'Enabled <i style="font-size:.7em;">(If you choose to disable it this menu item will not be available until you enable it)</i>',
        'value' => SdmForm::sdmFormSetDefaultInputValues(array('enabled' => true, 'disabled' => false), $menuItem->menuItemEnabled),
        'place' => '10',
    ),
    array(
        'id' => 'menuItemKeyholders',
        'type' => 'checkbox',
        'element' => 'Menu Item Keyholders <i style="font-size:.7em;">(Check the roles that should have access this menu item)</i>',
        'value' => SdmForm::sdmFormSetDefaultInputValues(array('Root' => 'root', 'Basic User' => 'basic_user', 'All' => 'all'), $menuItem->menuItemKeyholders),
        'place' => '11',
    ),
    array(
        'id' => 'menuItemWrappingTagType',
        'type' => 'select',
        'element' => 'Wrapping Tag Type <i style="font-size:.7em;">(The html tag to wrap this menu item with. NOTE:if any menu items use li, all menu items must use li so a list can be created. The form will enforce this.)</i>',
        'value' => ($firstMenuItemWrappingTagType === 'li' ? array('li' => 'li') : SdmForm::sdmFormSetDefaultInputValues(array('div' => 'div', 'p' => 'p', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6'), $menuItem->menuItemWrappingTagType)),
        'place' => '12',
    ),
    array(
        'id' => 'menuItemPosition',
        'type' => 'select',
        'element' => 'Menu Item Position <i style="font-size:.7em;">(Used to determine position relative to other menu items.)</i>',
        'value' => SdmForm::sdmFormSetDefaultInputValues(rangeArray(1, 50), $menuItem->menuItemPosition),
        'place' => '13',
    ),
);
$editMenuItemForm->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
$sdmassembler->sdmAssemblerIncorporateAppOutput($editMenuItemForm->sdmFormGetForm(), array('incpages' => array('navigationManagerEditMenuStage3_editmenuitem')));