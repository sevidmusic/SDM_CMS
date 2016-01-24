<?php

// load contentManager functions || this is only needed if this form requires the functions, some forms may not
require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/includes/contentManagerFunctions.php');

// CREATE EDIT FORM OBJECT
$editcontentform = new SdmForm();
$editcontentform->formHandler = 'contentManagerUpdateContentFormSubmission';
$editcontentform->method = 'post';
$editcontentform->formElements = array(
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
// we need to set the 'place' attribute to be start at a great integer then the form elements already in our formElements array, this form has defined 2 so we set our incrementer ($i) to 2
$i = 2;

// array of available pages
$available_pages = $sdmassembler->sdmCoreDetermineAvailablePages();
foreach ($sdmcms->sdmCmsDetermineAvailableWrappers() as $displayValue => $machineValue) {
    if (!in_array($machineValue, array_filter(arrstristrchars($available_pages, $machineValue)))) {
        array_push($editcontentform->formElements, array(
            'id' => $machineValue,
            'type' => 'textarea',
            'element' => "$displayValue (css id : <i>$machineValue</i>)",
            'value' => '',
            'place' => $i,
        ));
        $i++;
    }
}
$editcontentform->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
// add form to content
$sdmassembler->sdmAssemblerIncorporateAppOutput('<!-- contentManager Edit Content Form -->' . $editcontentform->sdmFormGetForm() . '<!-- End contentManager Edit Content Form -->', $options);