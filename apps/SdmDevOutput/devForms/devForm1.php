<?php
/**
 * Test form for testing SdmForm() class.
 * User: sevidmusic
 * Date: 4/17/16
 * Time: 1:57 PM
 */

/*** Custom form for testing SdmForm() class. ***/

/* Instantiate new form object. */
$customForm = new SdmForm();

/* Form handler */
$customForm->formHandler = 'SdmDevOutput';

/* Form method */
$customForm->method = 'post';

/* Determine whether form should preserve submitted values. */
$customForm->preserveSubmittedValues = true;

/* Form classes */
$customForm->formClasses = 'customFormBorder customFormPadding customFormBackgroundColor1'; // string definition
//$customForm->formClasses = explode(' ', 'classString class2 class3'); // array definition using explode()

/* Form submit button classes. */
$customForm->formSubmitButtonClasses = explode(' ', 'custom-form-submit'); // array definition using explode()

/** Form Elements **/

/* Text element. */
$customForm->sdmFormCreateFormElement('devText', 'text', 'Dev Text', 'Enter an email address', 0);

/* Text area element. */
$customForm->sdmFormCreateFormElement('devTextarea', 'textarea', 'Dev Textarea', 'Enter some text', 1);

/* Password element. */
$customForm->sdmFormCreateFormElement('devPassword', 'password', 'Dev Password', '', 2);

/* Select element items */
$customFormSelectItems = array('Item 1' => 'item1', 'Item 2' => 'item2', 'Item 3' => 'item3', 'Initially Selected Item' => 'item4');

/* Select element */
$customForm->sdmFormCreateFormElement('devSelect', 'select', 'Dev Select', $customForm->setDefaultValues($customFormSelectItems, 'item4'), 3);

/* Radio element items  */
$customFormRadioItems = array('Item 1' => 'item1', 'Item 2' => 'item2', 'Initially Selected Radio Item' => 'item3', 'item4' => 'item4');

/* Radio element. */
$customForm->sdmFormCreateFormElement('devRadio', 'radio', 'Dev Radio', $customForm->setDefaultValues($customFormRadioItems, 'item3'), 4);

/* Checkbox element items. */
$customFormCheckboxItems = array('Initially Selected Checkbox Item' => 'item1', 'Item 2' => 'item2', 'Item 3' => 'item3', 'item4' => 'item4');

/* Checkbox element .*/
$customForm->sdmFormCreateFormElement('devCheckbox', 'checkbox', 'Dev Checkbox', $customForm->setDefaultValues($customFormCheckboxItems, 'item1'), 4);

/* Hidden element form. */
$customForm->sdmFormCreateFormElement('devHidden', 'hidden', 'Dev Hidden', 'hidden value', 5);

/* Submit label. */
$customForm->submitLabel = 'Submit Custom Form';

/* Build form. */
$customForm->sdmFormBuildForm();

/* Build custom form element attributes. */
$customFormContainerAttributes = array(
    'elementType' => 'div',
    'classes' => array(
        'custom-form-container',
    ),
);
$customFormElementAttributes = array(
    'elementType' => 'div',
    'classes' => array(
        'custom-form-elements-container',
        'customFormPadding',
        'highlight',
    ),
);
$customFormHiddenElementAttributes = array(
    'elementType' => 'span'
);

/* Initialize $customFormElementsContainer html string. */
$customFormElementsContainer = '';

/* Build custom form table elements from individual custom form elements. */
$customFormElementsContainer .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devText'), $customFormElementAttributes);
$customFormElementsContainer .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devTextarea'), $customFormElementAttributes);
$customFormElementsContainer .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devPassword'), $customFormElementAttributes);
$customFormElementsContainer .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devSelect'), $customFormElementAttributes);
$customFormElementsContainer .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devRadio'), $customFormElementAttributes);
$customFormElementsContainer .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devCheckbox'), $customFormElementAttributes);
$customFormElementsContainer .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devHidden'), $customFormHiddenElementAttributes);

/* Build custom form container */
$customFormContainer = $sdmassembler->sdmAssemblerAssembleHtmlElement($customFormElementsContainer, $customFormContainerAttributes);

/* Gey any submitted form values. */
$submittedValues = $customForm->sdmFormGetSubmittedFormValue('all', $customForm->method);

/* If there are submitted values display them. */
if (!empty($submittedValues) && $submittedValues !== null) {
    /* Get submitted values */
    $submittedValuesDisplay = $sdmassembler->sdmCoreSdmReadArrayBuffered(['$submittedValues' => $submittedValues]);
}