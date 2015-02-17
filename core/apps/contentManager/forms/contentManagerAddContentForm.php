<?php

// load contentManager functions || this is only needed if this form requires the functions, some forms may not
require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

// CREATE EDIT FORM OBJECT
$editcontentform = new SDM_Form();
$editcontentform->form_handler = 'contentManagerUpdateContentFormSubmission';
$editcontentform->method = 'post';
$editcontentform->form_elements = array(
    array(
        'id' => 'page',
        'type' => 'text',
        'element' => 'Page Title',
        'value' => 'The Page Title',
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
// we need to set the 'place' attribute to be start at a great integer then the form elements already in our form_elements array, this form has defined 2 so we set our incrementer ($i) to 2
$i = 2;

// array of available pages
$available_pages = $sdmcore->sdmCoreDetermineAvailablePages();
foreach ($sdmcms->sdmCmsDetermineAvailableWrappers() as $displayValue => $machineValue) {
    if (!in_array($machineValue, array_filter(arrstristrchars($available_pages, $machineValue)))) {
        array_push($editcontentform->form_elements, array(
            'id' => $machineValue,
            'type' => 'textarea',
            'element' => "$displayValue (css id : <i>$machineValue</i>)",
            'value' => '',
            'place' => $i,
        ));
        $i++;
    }
}
$editcontentform->__build_form($sdmcore->getRootDirectoryUrl());
// add form to content
$sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<!-- contentManager Edit Content Form -->' . $editcontentform->__get_form() . '<!-- End contentManager Edit Content Form -->';