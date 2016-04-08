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

/* Build custom form  */
$customForm = new SdmForm();
$customForm->formHandler = 'SdmDevOutput';
$customForm->method = 'post';
$customForm->sdmFormCreateFormElement('customTextValue', 'text', 'Enter some text', '', 0);
$customForm->sdmFormCreateFormElement('customSelect', 'select', 'Select A Value', array('Select Value 1' => true, 'Select Value 2' => 420), 0);
$customForm->sdmFormCreateFormElement('customRadio', 'radio', 'Choose A Value', array('Radio Value 1' => true, 'Radio Value 2' => 420), 0);
$customForm->submitLabel = 'Submit Custom Form';
$customForm->sdmFormBuildForm();

$output .= '<h1>Demo Forms</h1>';
$output .= '<div class="sdm-dev-output-background sdm-dev-output-border sdm-dev-output-rounded-border sdm-dev-output-float-left sdm-dev-output-height sdm-dev-output-half-wide sdm-dev-output-padding-all sdm-dev-output-margin-bottom"><h3>Default Form</h3>' . $defaultForm->sdmFormGetForm() . '</div>';
$output .= '<div class="sdm-dev-output-background sdm-dev-output-border sdm-dev-output-rounded-border sdm-dev-output-float-right sdm-dev-output-height sdm-dev-output-half-wide sdm-dev-output-padding-all sdm-dev-output-margin-bottom"><h3>Custom Form</h3>' . $customForm->sdmFormGetForm() . '</div>';

$output .= '<div class="clear-both"></div>';
$output .= '<h3>Individual Form Elements</h3>';
$output .= $customForm->sdmFormOpenForm();
$output .= $customForm->sdmFormGetFormElementHtml('customSelect');
$output .= $customForm->sdmFormCloseForm();

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);
