<?php

/**
 * Select Theme form for the Content Manager core app.
 */

// CREATE EDIT FORM OBJECT
$editcontentform = new SdmForm();
$editcontentform->formHandler = 'contentManagerSelectThemeFormSubmission';
$editcontentform->method = 'post';
$available_themes = $sdmcms->sdmCmsDetermineAvailableThemes();
$current_theme = $sdmcms->sdmCoreDetermineCurrentTheme();
// set current theme as default value for select form
$filtered_available_themes = array();
foreach ($available_themes as $key => $value) {
    if ($value === $current_theme) {
        $filtered_available_themes[$key] = 'default_' . $value;
    } else {
        $filtered_available_themes[$key] = $value;
    }
}


$editcontentform->formElements = array(
    array(
        'id' => 'theme',
        'type' => 'select',
        'element' => 'Select Theme',
        'value' => $filtered_available_themes,
        'place' => '0',
    ),
    array(
        'id' => 'content_manager_form_submitted',
        'type' => 'hidden',
        'element' => 'content_manager_form_submitted',
        'value' => 'content_manager_form_submitted',
        'place' => '1',
    ),
);

$editcontentform->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
// add form to content
$sdmassembler->sdmAssemblerIncorporateAppOutput('<!-- contentManager Edit Content Form -->' . $editcontentform->sdmFormGetForm() . '<!-- End contentManager Edit Content Form -->', $options);