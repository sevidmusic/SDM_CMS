<?php

$menuId = SdmForm::sdmFormGetSubmittedFormValue('menuId');
$menuItemId = SdmForm::sdmFormGetSubmittedFormValue('menuItemId');
// get menu name | @todo : here, and anywhere else that needs to get menu name should use a method called getMenuDisplayName() which needs to be created...
$menu = $sdmassembler->sdmNmsGetMenu($menuId);
$menuName = $menu->menuDisplayName;
// get menu item name
$menuItemName = $menu->menuItems->$menuItemId->menuItemDisplayName;
// delete the menu item
$sdmassembler->sdmNmsDeleteMenuItem($menuId, $menuItemId);
$sdmassembler->sdmAssemblerIncorporateAppOutput('<p>Deleted menu item "<b>' . $menuItemName . '</b>" with id "<b>' . $menuItemId . '</b>" from menu "<b>' . $menuName . '</b>" with id "<b>' . $menuId . '</b>".</p>', array('incpages' => array('navigationManagerEditMenuStage3_deletemenuitem')));