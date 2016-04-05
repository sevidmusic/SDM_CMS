<?php

/**
 * Sdm Dev Output user app: This app is intended for use by developers. With it php
 * snippets can be tested. Just add the code to test to this file, and incorporate
 * the output via sdmAssemblerIncorporateAppOutput().
 */

$description = '<h1>Sdm Dev Output App</h1><p>This app is intended for use by developers.
                    With it php snippets can be tested. Just add the code to test to the
                    SdmDevOutput.php file.</p>';
$output = '<!-- Sdm Dev Output App Placeholder -->' . $description;

/* Uncomment to have app show an overview of the DataObject loaded for each page. */
//$sdmassembler->sdmCoreSdmReadArray($sdmassembler->info());


/* Build form*/
$devForm = new SdmForm();
$devForm->formHandler = 'SdmDevOutput';
$devForm->method = 'post';
/*
$devForm->formElements = array(
                            array(
                                'id' => 'devFormElement',
                                'type' => 'select',
                                'element' => 'Dev Form Element',
                                'value' => array(rand(0,100), rand(0,100)),
                                'place' => '0',
                            ),
                         );
*/
$devForm->submitLabel = 'Submit';

$devForm->sdmFormBuildForm();

/* Display form */
$output .= '<h1 style="text-align: center">--- Dev Form ---</h1>' . $devForm->sdmFormGetForm();

if ($devForm->sdmFormGetSubmittedFormValue('text_form_element')) {
    $output = '<h4 style="color:#00FF7F;">Form Submitted Successfully</h4>' . $output;
}
$sdmassembler->sdmCoreSdmReadArray($_POST);
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);
