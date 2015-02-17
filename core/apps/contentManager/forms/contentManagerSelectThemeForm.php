<?php

// CREATE EDIT FORM OBJECT
$editcontentform = new SDM_Form();
$editcontentform->form_handler = 'contentManagerSelectThemeFormSubmission';
$editcontentform->method = 'post';
$available_themes = $sdmcms->sdmCmsDetermineAvailableThemes();
$current_theme = $sdmcms->determineCurrentTheme();
// set current theme as default value for select form
$filtered_available_themes = array();
foreach ($available_themes as $key => $value) {
    if ($value === $current_theme) {
        $filtered_available_themes[$key] = 'default_' . $value;
    } else {
        $filtered_available_themes[$key] = $value;
    }
}


$editcontentform->form_elements = array(
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

$editcontentform->__build_form($sdmcore->getRootDirectoryUrl());
// add form to content
$sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<!-- contentManager Edit Content Form -->' . $editcontentform->__get_form() . '<!-- End contentManager Edit Content Form -->';