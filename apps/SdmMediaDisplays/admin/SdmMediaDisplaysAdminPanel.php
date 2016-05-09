<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:00 PM
 */

/* Create the Sdm Media Displays admin form. */
$sdmMediaDisplaysAdminForm = new SdmForm();

/* Determine which admin panel is currently in use. */
$defaultPanel = 'displayCrud'; // dev value placeholder for submitted form value 'panel'
$requestedPanel = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('panel');
$currentPanel = ($requestedPanel === null ? $defaultPanel : $requestedPanel);

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
$formHtml['formElementsHtml'] = implode(PHP_EOL, $currentPanelsFormElements);
$formHtml['closingFormTags'] = $sdmMediaDisplaysAdminForm->sdmFormCloseForm();

/* Display admin buttons for the current panel */
$completeFormHtml = implode('', $formHtml) . implode('', $currentPanelsButtons);
/* Incorporate Admin Panel. */
$sdmassembler->sdmAssemblerIncorporateAppOutput('<h1>Sdm Media Displays</h1>' . $completeFormHtml, array('incpages' => array('SdmMediaDisplays'), 'roles' => array('root'), 'incmethod' => 'prepend'));