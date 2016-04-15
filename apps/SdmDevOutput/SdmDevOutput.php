<?php

if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmDevOutput') {
    /**
     * Sdm Dev Output user app: This app is intended for use by developers. With it php
     * snippets can be tested. Just add the code to test to this file, and incorporate
     * the output via sdmAssemblerIncorporateAppOutput().
     */

    /* Identifying html comment. */
    $identifierComment = '<!-- Sdm Dev Output App -->';

    /* App description header. */
    $descriptionHeader = 'Sdm Dev Output App.';

    /* App description. */
    $description = 'This app is intended for use by developers. With it php snippets can be tested.';

    /* App description continued... */
    $description .= 'Just add the code to test to the SdmDevOutput.php file.';

    /* Dev Form */
    $defaultForm = new SdmForm();
    $defaultForm->formHandler = 'SdmDevOutput';
    $defaultForm->method = 'post';
    $defaultForm->sdmFormUseDefaultFormElements();
    $defaultForm->submitLabel = 'Submit';
    $defaultForm->sdmFormBuildForm();

    /* Element attributes for the $defaultForm's container element. */
    $defaultFormElementAttributes = array(
        'styles' => array(
            'background: #33CC66',
            'border: 3px solid #33CCFF',
            'border-radius: 5px',
            'padding: 20px'
        )
    );

    /* Form html. */
    $defaultFormHtml = $sdmassembler->sdmAssemblerAssembleHtmlElement($defaultForm->sdmFormGetForm(), $defaultFormElementAttributes);

    /* Get submitted values */
    $submittedValues = $defaultForm->sdmFormGetSubmittedFormValue('all', $defaultForm->method);
    $devOutput = $sdmassembler->sdmCoreSdmReadArrayBuffered(['$submittedValues' => $submittedValues]);
    /* If there are submitted values display them. */
    if (!empty($submittedValues) && $submittedValues !== null) {
        /* Initialize submittedValuesDisplay string. */
        $submittedValuesListItem = 'Form values submitted via ' . ($defaultForm->method === 'get' ? '$_GET' : '$_POST');

        /* Element attributes for submitted form display elements */
        $submittedKeyAttributes = array(
            'styles' => array(
                'background: #333333'
            ),
            'classes' => array(
                'sdm-dev-output-padding-all',
                'sdm-dev-output-rounded-border',
            ),
        );

        $submittedValueAttributes = array(
            'styles' => array(
                'background: #000000'
            ),
            'classes' => array(
                'sdm-dev-output-padding-all',
                'sdm-dev-output-rounded-border',
            ),
        );

        $submittedDisplayElementAttributes = array(
            'elementType' => 'li',
            'styles' => array(
                'color: palegreen',
                'font-size: .7em',
                'background: #33CCFF',
                'border: 3px solid #33CC66',
                'border-radius: 5px',
                'padding: 20px'
            )
        );

        /* Loop through $submittedValues adding key value pair to the $submittedValuesDisplay. */
        foreach ($submittedValues as $submittedKey => $submittedValue) {
            /* Display for $submittedKey */
            $submittedKeyDisplay = $sdmassembler->sdmAssemblerAssembleHtmlElement($submittedKey, $submittedKeyAttributes);

            /* Display $submittedValue */
            $submittedValueDisplay = $sdmassembler->sdmAssemblerAssembleHtmlElement($submittedValue, $submittedValueAttributes);

            /* Display the key and value as items in a list. */
            $submittedValuesListItem .= $sdmassembler->sdmAssemblerAssembleHtmlElement($submittedKeyDisplay . $submittedValueDisplay, $submittedDisplayElementAttributes);

        }

        /* Create an unordered list of submitted elements. */
        $submittedValuesList = $sdmassembler->sdmAssemblerAssembleHtmlElement($submittedValuesListItem, array('elementType' => 'ul'));
    }

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
    $output .= $devOutput;

    /* Output $defaultFormHtml */
    $output .= $defaultFormHtml;

    /* Display app $output */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);


}