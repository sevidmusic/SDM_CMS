<?php

// load contentManager functions
require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

$pagetoedit = $_POST['sdm_form']['page_to_edit'];
// CREATE EDIT FORM OBJECT
$editcontentform = new SDM_Form();
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
$available_pages = $sdmcore->sdmCoreDetermineAvailablePages();
// load in existing content to populate form fields
$existing_content = $sdmcore->sdmCoreLoadDataObject()->content->$pagetoedit;
// create form elements for appropriate wrappers | i.e., page specific wrappers will only be shown if $pagetoedit matches exists in the wrappers name
foreach ($sdmcms->sdmCmsDetermineAvailableWrappers() as $displayValue => $machineValue) {
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

$editcontentform->__build_form($sdmcore->getRootDirectoryUrl());
// add form to content
$sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '<!-- contentManager Edit Content Form --><p><i>You are currently editing the <b>' . ucwords($pagetoedit) . '</b></i></p>' . $editcontentform->__get_form() . '<!-- End contentManager Edit Content Form -->';