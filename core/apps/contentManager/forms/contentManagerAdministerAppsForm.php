<?php

/**
 * Administer Apps form for the Content Manager core app.
 */

// load contentManager functions || this is only needed if this form requires the functions, some forms may not
require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

// CREATE EDIT FORM OBJECT
$editcontentform = new SdmForm();
$editcontentform->formHandler = 'contentManagerAdministerAppsFormSubmission';
$editcontentform->method = 'post';
$available_apps = $sdmcms->sdmCmsDetermineAvailableApps();
$enabled_apps = $sdmcms->sdmCoreDetermineEnabledApps();
$editcontentform->formElements = array(
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
// Create form elements for each available app excluding required apps.
$dataObject = $sdmassembler->sdmCoreGetDataObject();
foreach ($available_apps as $displayValue => $machineValue) {
    switch (property_exists($dataObject->settings->requiredApps, $machineValue)) {
        case true:
            array_push($editcontentform->formElements, array(
                'id' => $machineValue,
                'type' => 'radio',
                'element' => "<span class='requiredApp'>$displayValue <i>(This app cannot be disabled because it is required by other apps.)</i></span>",
                'value' => array('on' => 'default_on'),
                'place' => $i,
            ));
            break;

        default:
            if (property_exists($enabled_apps, $machineValue)) {
                array_push($editcontentform->formElements, array(
                    'id' => $machineValue,
                    'type' => 'radio',
                    'element' => "$displayValue",
                    'value' => array('on' => 'default_on', 'off' => 'off'),
                    'place' => $i,
                ));
            } else {
                array_push($editcontentform->formElements, array(
                    'id' => $machineValue,
                    'type' => 'radio',
                    'element' => "$displayValue",
                    'value' => array('on' => 'on', 'off' => 'default_off'),
                    'place' => $i,
                ));
            }
            break;
    }
    $i++;
}

$editcontentform->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
// add form to content
$sdmassembler->sdmAssemblerIncorporateAppOutput('<!-- contentManager Edit Content Form -->' . $editcontentform->sdmFormGetForm() . '<!-- End contentManager Edit Content Form -->', $options);