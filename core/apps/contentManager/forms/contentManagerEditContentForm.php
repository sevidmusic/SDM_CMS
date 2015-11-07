<?php

// load contentManager functions
require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

// CREATE EDIT FORM OBJECT
$editcontentform = new SdmForm();
$pagetoedit = $editcontentform->sdmFormGetSubmittedFormValue('page_to_edit');
$editcontentform->form_handler = 'contentManagerUpdateContentFormSubmission';
$editcontentform->method = 'post';
$editcontentform->form_elements = array(
    array(
        'id' => 'page',
        'type' => 'hidden',
        'element' => 'page',
        'value' => $pagetoedit,
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
// incrementer to determine place of additional form elements
$i = 2;
// array of available pages
$available_pages = $sdmassembler->sdmCoreDetermineAvailablePages();
// load in existing content to populate form fields
$existing_content = $sdmassembler->sdmCoreLoadDataObject()->content->$pagetoedit;
// create form elements for appropriate wrappers | i.e., page specific wrappers will only be shown if $pagetoedit matches exists in the wrappers name
foreach ($sdmcms->sdmCmsDetermineAvailableWrappers() as $displayValue => $machineValue) {
    // create place holder string if any wrappers that do not exist in core
    if (!isset($existing_content->$machineValue) === TRUE) {
        $existing_content->$machineValue = '';
    }
    if (!in_array($machineValue, array_filter(arrstristrchars($available_pages, $machineValue)))) {
        array_push($editcontentform->form_elements, array(
            'id' => $machineValue,
            'type' => 'textarea',
            'element' => "$displayValue (css id : <i>$machineValue</i>)",
            'value' => str_replace('&lt;br &sol;&gt;', '', $existing_content->$machineValue),
            'place' => $i,
        ));
        $i++;
    }
    // if the $pagetoedit is found in the wrapper ($machineValue) string, then create a form element b/c the page were editng is relavent to the page specific content.
    if (strlen(stristr($machineValue, $pagetoedit)) > 0) {
        array_push($editcontentform->form_elements, array(
            'id' => $machineValue,
            'type' => 'textarea',
            'element' => "$displayValue (css id : <i>$machineValue</i>)",
            'value' => str_replace('&lt;br &sol;&gt;', '', $existing_content->$machineValue),
            'place' => $i,
        ));
        $i++;
    }
}

$editcontentform->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
// add form to content
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<!-- contentManager Edit Content Form --><p><i>You are currently editing the <b>' . ucwords($pagetoedit) . '</b></i></p>' . $editcontentform->sdmFormGetForm() . '<!-- End contentManager Edit Content Form -->', $options);