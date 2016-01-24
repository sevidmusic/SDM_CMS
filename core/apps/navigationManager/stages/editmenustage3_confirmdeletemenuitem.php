<?php

$menuId = (SdmForm::sdmFormGetSubmittedFormValue('menuId') !== null ? SdmForm::sdmFormGetSubmittedFormValue('menuId') : $_GET['menuId']);
$menuItemId = (SdmForm::sdmFormGetSubmittedFormValue('menuItemId') !== null ? SdmForm::sdmFormGetSubmittedFormValue('menuItemId') : $_GET['menuItemId']);
// get menu name | @todo : here, and anywhere else that needs to get menu name should use a method called getMenuDisplayName() which needs to be created...
$menu = $sdmassembler->sdmNmsGetMenu($menuId);
$menuName = $menu->menuDisplayName;
// get menu item name
$menuItemName = $menu->menuItems->$menuItemId->menuItemDisplayName;


// delete form
$deleteForm = new SdmForm();
$deleteForm->method = 'post';
$deleteForm->formHandler = 'navigationManagerEditMenuStage3_deletemenuitem';
$deleteForm->submitLabel = 'Delete Menu Item';
$deleteForm->formElements = array(
    array(
        'id' => 'menuId',
        'type' => 'hidden',
        'element' => 'Hidden',
        'value' => $menuId,
        'place' => '0',
    ),
    array(
        'id' => 'menuItemId',
        'type' => 'hidden',
        'element' => 'Hidden',
        'value' => $menuItemId,
        'place' => '1',
    ),
);
$deleteForm->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
// cancel form
$cancelForm = new SdmForm();
$cancelForm->formHandler = 'navigationManagerEditMenuStage2';
$cancelForm->submitLabel = 'Cancel';
$cancelForm->formElements = array(
    array(
        'id' => 'menuId',
        'type' => 'hidden',
        'element' => 'Hidden',
        'value' => $menuId,
        'place' => '0',
    ),
);
$cancelForm->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
$sdmassembler->sdmAssemblerIncorporateAppOutput('<p>Are you sure you wish to delete menu item "<b>' . $menuItemName . '</b>" with id "<b>' . $menuItemId . '</b>" from menu "<b>' . $menuName . '</b>" with id "<b>' . $menuId . '</b>"?</p>' . $deleteForm->sdmFormGetForm() . $cancelForm->sdmFormGetForm(), array('incpages' => array('navigationManagerEditMenuStage3_confirmdeletemenuitem')));