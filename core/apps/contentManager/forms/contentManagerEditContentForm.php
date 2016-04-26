<?php

/**
 * Edit content form for the Content Manager core app.
 */

// load contentManager functions
require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

// CREATE EDIT FORM OBJECT
$editcontentform = new SdmForm();
$pageToEdit = $editcontentform->sdmFormGetSubmittedFormValue('page_to_edit');
$editcontentform->formHandler = 'contentManagerUpdateContentFormSubmission';
$editcontentform->method = 'post';
$editcontentform->formElements = array(
    array(
        'id' => 'page',
        'type' => 'hidden',
        'element' => 'page',
        'value' => $pageToEdit,
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
$existing_content = $sdmassembler->sdmCoreLoadDataObject(false)->content->$pageToEdit;
// create form elements for appropriate wrappers | i.e., page specific wrappers will only be shown if $pageToEdit matches exists in the wrappers name
foreach ($sdmcms->sdmCmsDetermineAvailableWrappers($pageToEdit) as $displayValue => $machineValue) {
    // create place holder string if any wrappers that do not exist in core
    if (!isset($existing_content->$machineValue) === true) {
        $existing_content->$machineValue = '';
    }
    /* If $machineValue (wrapper) is not page specific, and an element does not
       already exist for this wrapper in the form. */
    if (!in_array($machineValue, array_filter(arrstristrchars($available_pages, $machineValue))) || strlen(stristr($machineValue, $pageToEdit)) > 0) {
        array_push($editcontentform->formElements, array(
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
$sdmassembler->sdmAssemblerIncorporateAppOutput('<!-- contentManager Edit Content Form --><p><i>You are currently editing the <b>' . ucwords($pageToEdit) . '</b></i></p>' . $editcontentform->sdmFormGetForm() . '<!-- End contentManager Edit Content Form -->', $options);