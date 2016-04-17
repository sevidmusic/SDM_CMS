<?php

if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmDevOutput') {
    /**
     * Sdm Dev Output user app: This app is intended for use by developers. With it php
     * snippets can be tested. Just add the code to test to this file, and incorporate
     * the output via sdmAssemblerIncorporateAppOutput().
     */

    /*** App generated content ***/

    /* Identifying html comment. */
    $identifierComment = '<!-- Sdm Dev Output App -->';

    /* App description header. */
    $descriptionHeader = 'Sdm Dev Output App.';

    /* App description. */
    $description = 'This app is intended for use by developers. With it php snippets can be tested.';

    /* App description continued... */
    $description .= 'Just add the code to test to the SdmDevOutput.php file.';

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
    $tableAttributes = array(
        'elementType' => 'div',
        'classes' => array(
            'custom-form-table',
        )
    );
    $trAttributes = array(
        'elementType' => 'div',
        'classes' => array(
            'custom-form-tr',
        ),
    );
    $tdAttributes = array(
        'elementType' => 'div',
        'classes' => array(
            'custom-form-td',
        ),
    );

    /* Initialize $customFormTableRow1Elements html string. */
    $customFormTableRow1Elements = '';

    /* Build custom form table elements from individual custom form elements. */
    $customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devText'), $tdAttributes);
    $customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devTextarea'), $tdAttributes);
    $customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devPassword'), $tdAttributes);
    $customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devSelect'), $tdAttributes);
    $customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devRadio'), $tdAttributes);
    $customFormTableRow1Elements .= $sdmassembler->sdmAssemblerAssembleHtmlElement($customForm->sdmFormGetFormElementHtml('devCheckbox'), $tdAttributes);

    /* Build custom form table row 1 */
    $customFormTableRow1 = $sdmassembler->sdmAssemblerAssembleHtmlElement($customFormTableRow1Elements, $trAttributes);

    /* Build custom form table */
    $customFormTable = $sdmassembler->sdmAssemblerAssembleHtmlElement($customFormTableRow1, $tableAttributes);

    /* Add $customForm's hidden element below the table. */
    $customFormTable .= $customForm->sdmFormGetFormElementHtml('devHidden');

    /* Gey any submitted form values. */
    $submittedValues = $customForm->sdmFormGetSubmittedFormValue('all', $customForm->method);

    /* If there are submitted values display them. */
    if (!empty($submittedValues) && $submittedValues !== null) {
        /* Get submitted values */
        $devOutput = $sdmassembler->sdmCoreSdmReadArrayBuffered(['$submittedValues' => $submittedValues]);
    }

    /** App $output **/

    /* Output $identifierComment */
    $output = $identifierComment;

    /* Output $descriptionHeader */
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($descriptionHeader, array('elementType' => 'h2'));

    /* Output $description */
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($description, array('elementType' => 'p'));

    if (isset($submittedValuesList) === true) {
        /* Output $submittedValuesList */
        $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($submittedValuesList, array('elementType' => 'div'));
    }

    /* DEV OUTPUT */
    if (isset($devOutput) === true) {
        $output .= $devOutput;
    }

    /* Output $customFormHtml */
    $output .= $customForm->sdmFormOpenForm() . $customFormTable . $customForm->sdmFormCloseForm();

    /* Display app $output */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);


}