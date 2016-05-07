<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:00 PM
 */

/* SdmMediaDisplays Admin Form | Define fomr properties. */
$sdmMediaDisplaysAdminForm = new SdmForm();
$sdmMediaDisplaysAdminForm->preserveSubmittedValues = true;

/* Load admin buttons. */
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/buttons/SdmMediaDisplaysAdminButtons.php');

/* Load form elements. */
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/sdmMediaDisplaysAdminFormElements.php');

/* Initialize $formHtml. */
$formHtml = array();

/* Start building form. */
$formHtml['openingFormTags'] = $sdmMediaDisplaysAdminForm->sdmFormOpenForm($sdmassembler->sdmCoreGetRootDirectoryUrl());


/* Finish building form. */
$formHtml['closingFormTags'] = $sdmMediaDisplaysAdminForm->sdmFormCloseForm();
/* Incorporate Admin Panel. */
$sdmassembler->sdmAssemblerIncorporateAppOutput('<h1>Sdm Media Displays</h1>', array('incpages' => array('SdmMediaDisplays'), 'roles' => array('root'), 'incmethod' => 'prepend'));