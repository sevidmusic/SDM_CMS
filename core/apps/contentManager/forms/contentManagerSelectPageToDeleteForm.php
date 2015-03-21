<?php

// load contentManager functions || this is only needed if this form requires the functions, some forms may not
require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

// determine available pages
$available_pages = $sdmcore->sdmCoreDetermineAvailablePages();
// filter out "Content Manager" pages so they can not be editied
$editable_pages = array_filter($available_pages, 'filter_content_manager_pages');
// CREATE EDIT FORM OBJECT
$editcontentform = new SDM_Form();
$editcontentform->form_handler = 'contentManagerDeletePageSubmission';
$editcontentform->method = 'post';
$editcontentform->form_elements = array(
    array(
        'id' => 'page_to_delete',
        'type' => 'select',
        'element' => 'Page To Delete',
        'value' => $editable_pages,
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
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<!-- contentManager Edit Content Form -->' . $editcontentform->__get_form() . '<!-- End contentManager Edit Content Form -->', $options);