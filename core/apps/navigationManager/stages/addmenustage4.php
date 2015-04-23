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
$sdmnms->sdmNmsAddMenu($menu);
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<p>Menu Added Successfully (Still in Dev, Does not necessarily indicate succsessful menu add yet)</p>', array('incpages' => array('navigationManagerAddMenuStage4')));

