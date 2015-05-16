<?php

/** LOAD AND PREP EXISTING MENU DATA FOR EDIT MENU FORM * */
// get menu id from edit menu stage 1 form submission
$menuId = SdmForm::sdmFormGetSubmittedFormValue('menuId');
// use menu id to load the menu we want to edit
$menu = $sdmnms->sdmNmsGetMenu($menuId);
// get the menu html for the menu we want to edit, this will be used as a preview of the menu in it's current state
$menuHtml = $sdmnms->sdmNmsBuildMenuHtml($menu);
// format current menu properties for use with edit menu form
// determine available wrappers | run through SdmForm::setDefaultValues to set current wrapper as default element
$availableWrappers = SdmForm::setDefaultValues($sdmcms->sdmCmsDetermineAvailableWrappers(), $menu->wrapper);
// get first menu item's wrapping tag type, this is used to determine which tag types should be available to the form element menuWrappingTagType
$firstMenuItemWrappingTagType = $menu->menuItems[0]->menuItemWrappingTagType;
// if any menu items are wrapped with li then only ul should be available, otherwise call SdmForm::setDefaultValus()
$menuWrappingTagType = ($firstMenuItemWrappingTagType === 'li' ? array('ul' => 'ul') : SdmForm::setDefaultValues(array('div' => 'div', 'p' => 'p', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6'), $menu->menuWrappingTagType));
// determine keyholders | run through SdmForm::setDefaultValues to set current keyholders as default elements | @todo: Until user and roles components are developed the available roles are root, basic_user, and all
$keyholders = SdmForm::setDefaultValues(array('Root' => 'root', 'Basic User' => 'basic_user', 'All' => 'all'), $menu->menuKeyholders);
// determine display pages
$displayPages = SdmForm::setDefaultValues(array_merge($sdmcore->sdmCoreDetermineAvailablePages(), json_decode(json_encode($sdmcore->sdmCoreDetermineEnabledApps()), TRUE), array('all' => 'all')), $menu->displaypages);
// determine menu placement
$menuPlacement = SdmForm::setDefaultValues(array('prepend' => 'prepend', 'append' => 'append'), $menu->menuPlacement);
/** BUILD EDIT MENU FORM * */
$editMenuSelectMenuForm = new SdmForm();
$editMenuSelectMenuForm->form_method = 'post';
$editMenuSelectMenuForm->form_handler = 'navigationManagerEditMenuStage3_submitmenuchanges';
$editMenuSelectMenuForm->submitLabel = 'Submit Changes to Menu';
$editMenuSelectMenuForm->form_elements = array(
    array(
        'id' => 'menuId',
        'type' => 'hidden',
        'element' => 'Menu Id',
        'value' => $menuId,
        'place' => '1',
    ),
    array(
        'id' => 'menuDisplayName',
        'type' => 'text',
        'element' => 'Display Name <i style="font-size:.7em;">(The display name to use for this menu.)</i>',
        'value' => $menu->menuDisplayName,
        'place' => '3',
    ),
    array(
        'id' => 'menuCssId',
        'type' => 'text',
        'element' => 'Menu Css Id <i style="font-size:.7em;">(The css id for this menu.)</i>',
        'value' => $menu->menuCssId,
        'place' => '4',
    ),
    array(
        'id' => 'menuCssClasses',
        'type' => 'text',
        'element' => 'Menu Css Classes<i style="font-size:.7em;">(The css classes for this menu.)</i>',
        'value' => implode(',', $menu->menuCssClasses),
        'place' => '5',
    ),
    array(
        'id' => 'wrapper',
        'type' => 'select',
        'element' => 'Wrapper <i style="font-size:.7em;">(The content wrapper to place the menu in.)</i>',
        'value' => $availableWrappers,
        'place' => '6',
    ),
    array(// @TODO we need to check all menu items for li, we also need to filter set any menu items not set to li to li if any menu item is set to li. This is not urgent.
        'id' => 'menuWrappingTagType',
        'type' => 'select',
        'element' => 'Wrapping Tag Type <i style="font-size:.7em;">(The html tag to wrap this menu item with. NOTE: If li was used for any menu items then ul is enforced so a list can be created.)</i>',
        'value' => $menuWrappingTagType,
        'place' => '7',
    ),
    array(
        'id' => 'menuPlacement',
        'type' => 'select',
        'element' => 'Menu Placement <i style="font-size:.7em;">(Determines the placement of the menu in relation to the other content in the content wrapper this menu is being placed in. Prepend will place menu above other content, append will place it below other content.)</i>',
        'value' => $menuPlacement,
        'place' => '8',
    ),
    array(
        'id' => 'displaypages',
        'type' => 'checkbox',
        'element' => 'Pages to display menu on<i style="font-size:.7em;">(THIS NEEDS TO BE FIGURED OUT BETTER, POSSIBLY A CHECKLIST OF AVAILABLE PAGES...) FOR NOW all IS THE ONLY OPTION</i>',
        'value' => $displayPages,
        'place' => '9',
    ),
    array(
        'id' => 'menuKeyholders',
        'type' => 'checkbox',
        'element' => 'Keyholders',
        'value' => $keyholders,
        'place' => '10',
    ),
    array(
        'id' => 'menuItems',
        'type' => 'hidden',
        'element' => 'Menu Items',
        'value' => $menu->menuItems,
        'place' => '14',
    ),
);
$editMenuSelectMenuForm->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
$output = '<div id="originalMenuPreview" style="padding:20px;border:3px dashed #777777;border-radius:7px;"><h4>Menu Preview</h4>' . $menuHtml . '</div>';
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output . $editMenuSelectMenuForm->sdmFormGetForm(), array('incpages' => array('navigationManagerEditMenuStage2')));
