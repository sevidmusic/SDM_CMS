<?php

// load contentManager functions || this is only needed if this form requires the functions, some forms may not
require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

// CREATE EDIT FORM OBJECT
$editcontentform = new SdmForm();
$editcontentform->form_handler = 'contentManagerAdministerAppsFormSubmission';
$editcontentform->method = 'post';
$available_apps = $sdmcms->sdmCmsDetermineAvailableApps();
$enabled_apps = $sdmcms->sdmCoreDetermineEnabledApps();
$editcontentform->form_elements = array(
    array(
        'id' => 'content_manager_form_submitted',
        'type' => 'hidden',
        'element' => 'content_manager_form_submitted',
        'value' => 'content_manager_form_submitted',
        'place' => '0',
    ),
);
// incrementer to determine place of additional form elements
$i = 1;
// Create form elements for each available app
foreach ($available_apps as $displayValue => $machineValue) {
    if (property_exists($enabled_apps, $machineValue)) {
        array_push($editcontentform->form_elements, array(
            'id' => $machineValue,
            'type' => 'radio',
            'element' => "$displayValue",
            'value' => array('on' => 'default_on', 'off' => 'off'),
            'place' => $i,
        ));
    } else {
        array_push($editcontentform->form_elements, array(
            'id' => $machineValue,
            'type' => 'radio',
            'element' => "$displayValue",
            'value' => array('on' => 'on', 'off' => 'default_off'),
            'place' => $i,
        ));
    }
    $i++;
}

$editcontentform->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
// add form to content
$sdmassembler->sdmAssemblerIncorporateAppOutput('<!-- contentManager Edit Content Form -->' . $editcontentform->sdmFormGetForm() . '<!-- End contentManager Edit Content Form -->', $options);