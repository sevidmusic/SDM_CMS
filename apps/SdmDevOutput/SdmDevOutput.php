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

$output .= '<h3>Default Form</h3>' . $defaultForm->sdmFormGetForm();
$output .= '<h3>Custom Form</h3>' . $customForm->sdmFormGetForm();


$output .= '<h3>Individual Form Elements</h3>';
$output .= $customForm->sdmFormOpenForm();
$output .= $customForm->sdmFormGetFormElementHtml('customSelect');
$output .= $customForm->sdmFormCloseForm();

$debug = debug_backtrace();
$debug = array_reverse($debug);
$devArr = array(
    'Default Form' => $defaultForm,
    'Int' => 420,
    'Float' => 3240923.2348,
    'Bool True' => true,
    'Bool False' => false,
    'Null' => null,
    'Zero' => 0,
    'Sub Array' => array(
        'sub bool' => true,
        'sub bool 2' => false,
        'sub int' => 420,
        'sub float' => 23482.234,
        'sub zero' => 0,
        'sub null' => null,
        'sub sub array' => array(
            'Custom Form' => $customForm,
            'adf did s9d dke' => 'sfdjdw a dd kdisd diohjejkndsiohas sdid88j3 sd.',
            'sub sub sub array' => array(
                'sub default form object' => $defaultForm,
                'asd',
                'd9ek3',
                'as0dk-s9dks9dk',
                3934.34,
                null,
                true,
                false,
            )
        ),
    ),
    'debug_backtrace() output' => $debug,
);

$devData = $sdmassembler->sdmCoreSdmReadArrayBuffered($devArr);

$output .= $devData;

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);
