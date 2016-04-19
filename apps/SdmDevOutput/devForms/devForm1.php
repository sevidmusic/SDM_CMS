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
$containerAttributes = array(
    'elementType' => 'div',
    'classes' => array(
        'custom-form-tr',
    ),
);
$elementContainerAttributes = array(
    'elementType' => 'div',
    'classes' => array(
        'custom-form-td',
    ),
);

/* Initialize $customFormTableRow1Elements html string. */
$customFormTableRow1Elements = '';

/* Build custom form table elements from individual custom form elements. */
$customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devText'), $elementContainerAttributes);
$customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devTextarea'), $elementContainerAttributes);
$customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devPassword'), $elementContainerAttributes);
$customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devSelect'), $elementContainerAttributes);
$customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devRadio'), $elementContainerAttributes);
$customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devCheckbox'), $elementContainerAttributes);
$customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devHidden'), array('elementType' => 'span'));

/* Build custom form table row 1 */
$customFormDisplayContainer = $sdmassembler->sdmAssemblerAssembleHtmlElement($customFormTableRow1Elements, $containerAttributes);

/* Gey any submitted form values. */
$submittedValues = $customForm->sdmFormGetSubmittedFormValue('all', $customForm->method);

/* If there are submitted values display them. */
if (!empty($submittedValues) && $submittedValues !== null) {
    /* Get submitted values */
    $submittedValuesDisplay = $sdmassembler->sdmCoreSdmReadArrayBuffered(['$submittedValues' => $submittedValues]);
}