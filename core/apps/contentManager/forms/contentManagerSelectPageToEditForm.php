<?php

// load contentManager functions || this is only needed if this form requires the functions, some forms may not
require_once($sdmcore->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

// determine available pages
$available_pages = $sdmcore->sdmCoreDetermineAvailablePages();
// filter out "Content Manager" pages so they can not be editied
$editable_pages = array_filter($available_pages, 'filter_content_manager_pages');
// CREATE EDIT FORM OBJECT
$editcontentform = new SdmForm();
$editcontentform->form_handler = 'contentManagerEditContentForm';
$editcontentform->method = 'post';
$editcontentform->form_elements = array(
    array(
        'id' => 'page_to_edit',
        'type' => 'select',
        'element' => 'Page To Edit',
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

$editcontentform->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
// add form to content
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<!-- contentManager Edit Content Form -->' . $editcontentform->sdmFormGetForm() . '<!-- End contentManager Edit Content Form -->', $options);