<?php

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
$sdmnms->sdmNmsUpdateMenu($menu->menuId, $menu);
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmAssemblerDataObject, '<p>Menu Edits Saved Successfully</p>', array('incpages' => array('navigationManagerEditMenuStage3_submitmenuchanges')));