<?php

/*
 * Stores final menu item configuration form data,
 * Sets up Menu configuration form.
 *
 */
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
$finalSubmittedMenuItem->menuItemKeyholders = SdmForm::sdmFormGetSubmittedFormValue('menuItemKeyholders');
$finalSubmittedMenuItem->menuItemMachineName = SdmCore::SdmCoreGenerateMachineName(SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName'));
$finalSubmittedMenuItem->menuItemPosition = SdmForm::sdmFormGetSubmittedFormValue('menuItemPosition');
$finalSubmittedMenuItem->menuItemWrappingTagType = SdmForm::sdmFormGetSubmittedFormValue('menuItemWrappingTagType');
// add the last submitted menu item to our menu items array
array_push($menuItems, $finalSubmittedMenuItem);
// display of preview of the menu so far
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<div style="border:2px solid #777777;border-radius:9px;padding:20px;height:120px;overflow:auto;"><h3>Last Submitted Menu Item:</h3><p>Display Name: <span style="color:blue;">' . SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName') . '</span> | Destination Type : <span style="color:blue;">' . $finalSubmittedMenuItem->destinationType . '</span> | Destination: <span style="color:blue;">' . $finalSubmittedMenuItem->destination . '</span></p><h3>Menu Preview:</h3>' . $sdmnms->sdmNmsBuildMenuItemsHtml($menuItems) . '</div>', array('incmethod' => 'prepend', 'incpages' => $options['incpages']));
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
        'value' => '',
        'place' => '3',
    ),
    array(
        'id' => 'menuCssId',
        'type' => 'text',
        'element' => 'Menu Css Id <i style="font-size:.7em;">(The css id for this menu.)</i>',
        'value' => '',
        'place' => '4',
    ),
    array(
        'id' => 'menuCssClasses',
        'type' => 'text',
        'element' => 'Menu Css Classes<i style="font-size:.7em;">(The css classes for this menu.)</i>',
        'value' => '',
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
        'type' => 'checkbox',
        'element' => 'Pages to display menu on<i style="font-size:.7em;">(THIS NEEDS TO BE FIGURED OUT BETTER, POSSIBLY A CHECKLIST OF AVAILABLE PAGES...) FOR NOW all IS THE ONLY OPTION</i>',
        'value' => array_merge($sdmcore->sdmCoreDetermineAvailablePages(), json_decode(json_encode($sdmcore->sdmCoreDetermineEnabledApps()), TRUE), array('all' => 'all')),
        'place' => '9',
    ),
    array(
        'id' => 'menuKeyholders',
        'type' => 'checkbox',
        'element' => 'Keyholders',
        'value' => array('Root' => 'root', 'Basic User' => 'basic_user', 'All' => 'all'),
        'place' => '10',
    ),
);
$addMenuFormStage3->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $addMenuFormStage3->sdmFormGetForm(), array('incpages' => array('navigationManagerAddMenuStage3')));
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h3>Configure Menu</h3>', array('incmethod' => 'prepend', 'incpages' => array('navigationManagerAddMenuStage3')));