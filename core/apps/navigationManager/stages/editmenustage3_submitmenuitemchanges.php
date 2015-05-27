<?php

// create new menu item object using last submitted menu items data
$submittedMenuItem = new SdmMenuItem();
$submittedMenuItem->arguments = explode(',', SdmForm::sdmFormGetSubmittedFormValue('arguments'));
$submittedMenuItem->destination = (SdmForm::sdmFormGetSubmittedFormValue('destinationType') === 'external' ? SdmForm::sdmFormGetSubmittedFormValue('destinationExternal') : SdmForm::sdmFormGetSubmittedFormValue('destinationInternal'));
$submittedMenuItem->destinationType = SdmForm::sdmFormGetSubmittedFormValue('destinationType');
$submittedMenuItem->menuItemCssClasses = explode(',', SdmForm::sdmFormGetSubmittedFormValue('menuItemCssClasses'));
$submittedMenuItem->menuItemCssId = SdmForm::sdmFormGetSubmittedFormValue('menuItemCssId');
$submittedMenuItem->menuItemDisplayName = SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName');
$submittedMenuItem->menuItemEnabled = SdmForm::sdmFormGetSubmittedFormValue('menuItemEnabled');
$submittedMenuItem->menuItemId = SdmForm::sdmFormGetSubmittedFormValue('menuItemId');
$submittedMenuItem->menuItemKeyholders = SdmForm::sdmFormGetSubmittedFormValue('menuItemKeyholders');
$submittedMenuItem->menuItemMachineName = SdmCore::SdmCoreGenerateMachineName(SdmForm::sdmFormGetSubmittedFormValue('menuItemDisplayName'));
$submittedMenuItem->menuItemPosition = SdmForm::sdmFormGetSubmittedFormValue('menuItemPosition');
$submittedMenuItem->menuItemWrappingTagType = SdmForm::sdmFormGetSubmittedFormValue('menuItemWrappingTagType');
$sdmcore->SdmCoreSdmReadArray($submittedMenuItem);
$sdmnms->sdmNmsUpdateMenuItem(SdmForm::sdmFormGetSubmittedFormValue('menuId'), $submittedMenuItem->menuItemId, $submittedMenuItem);
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<p>Menu Item Edits Saved Successfully</p>', array('incpages' => array('navigationManagerEditMenuStage3_submitmenuitemchanges')));