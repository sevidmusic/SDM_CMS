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

/* Test SdmForm() changes. */

/* Build form using default values */
$defaultForm = new SdmForm();
$defaultForm->formHandler = 'SdmDevOutput';
$defaultForm->method = 'post';
$defaultForm->sdmFormUseDefaultFormElements();
$defaultForm->submitLabel = 'Submit';
$defaultForm->sdmFormBuildForm();

$attributes = array(
    'classes' => explode(' ', 'sdm-dev-output-background sdm-dev-output-border sdm-dev-output-rounded-border sdm-dev-output-float-left sdm-dev-output-height sdm-dev-output-half-wide sdm-dev-output-padding-all sdm-dev-output-margin-bottom'),
    'elementType' => 'div',
);
//$output .= $sdmassembler->sdmCoreSdmReadArrayBuffered($attributes);
$output .= $sdmassembler->sdmAssemblerAssembleHtmlElement('<h3>Default Form</h3>' . $defaultForm->sdmFormGetForm(), $attributes);
$output .= '<div class="sdm-dev-output-clear-both"></div>';

$output .= '<ul>';
foreach($defaultForm->sdmFormGetSubmittedFormValue() as $submittedKey => $submittedValue) {
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($submittedKey . ' => ' . $submittedValue, array('elementType' => 'li', 'styles' => array('color: palegreen', 'font-size: .7em')));
}
$output .= '</ul>';

/* variable variables
$post = $_POST;
$get = $_GET;
$session = $_SESSION;
$method = 'session';
$data = $$method;
*/
$output .= $sdmassembler->sdmCoreSdmReadArrayBuffered($data);

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);






/*
 $submittedDefaultFormValues = $defaultForm->sdmFormGetSubmittedFormValue();
foreach ($submittedDefaultFormValues as $valKey => $valValue) {
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($valKey . ': ' . $valValue, array('elementType' => 'p', 'styles' => array('color: red')));
}
 */