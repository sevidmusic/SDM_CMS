<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:00 PM
 */

/* Create the Sdm Media Displays admin form. */
$sdmMediaDisplaysAdminForm = new SdmForm();

/* SdmMediaDisplays Admin Form | Define form properties. */
$sdmMediaDisplaysAdminForm->preserveSubmittedValues = true;
$sdmMediaDisplaysAdminForm->excludeSubmitLabel = true; // exclude default submit label.

/* Load admin buttons. */
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/buttons/SdmMediaDisplaysAdminButtons.php');

/* Load form elements. */
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/sdmMediaDisplaysAdminFormElements.php');

/* Initialize $formHtml. */
$formHtml = array();

/* Start building form. */
$formHtml['openingFormTags'] = $sdmMediaDisplaysAdminForm->sdmFormOpenForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
// FORM ELEMENTS GO HERE //
/* Finish building form. */
$formHtml['closingFormTags'] = $sdmMediaDisplaysAdminForm->sdmFormCloseForm();

/* Display admin buttons for the current panel */
$sdmassembler->sdmAssemblerIncorporateAppOutput(implode('', $formHtml) . implode('', $currentPanelsButtons), array('incpages' => array('SdmMediaDisplays')));

/* Incorporate Admin Panel. */
$sdmassembler->sdmAssemblerIncorporateAppOutput('<h1>Sdm Media Displays</h1>', array('incpages' => array('SdmMediaDisplays'), 'roles' => array('root'), 'incmethod' => 'prepend'));