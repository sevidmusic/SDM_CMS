<?php

if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmDevOutput') {
    /**
     * Sdm Dev Output user app: This app is intended for use by developers. With it php
     * snippets can be tested. Just add the code to test to this file, and incorporate
     * the output via sdmAssemblerIncorporateAppOutput().
     */

    $description = '<h1>Sdm Dev Output App</h1><p>This app is intended for use by developers.
                    With it php snippets can be tested. Just add the code to test to the
                    SdmDevOutput.php file.</p>';
    $output = '<!-- Sdm Dev Output App Placeholder -->' . $description;

    /* DEV FORM */
    $defaultForm = new SdmForm();
    $defaultForm->formHandler = 'SdmDevOutput';
    $defaultForm->method = 'post';
    $defaultForm->sdmFormUseDefaultFormElements();
    $defaultForm->submitLabel = 'Submit';
    $defaultForm->sdmFormBuildForm();
    $defaultFormHtml = $defaultForm->sdmFormGetForm();

    /* DISPLAY FORM */
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($defaultFormHtml, array('styles' => array('background: #33CC66', 'border: 3px solid #33CCFF', 'border-radius: 5px', 'padding: 20px')));
    /* DISPLAY SUBMITTED FORM VALUES */
    $output .= '<ul>';
    $submittedValues = $defaultForm->sdmFormGetSubmittedFormValue();
    if (!empty($submittedValues)) {
        foreach ($submittedValues as $submittedKey => $submittedValue) {
            $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement(
                $sdmassembler->sdmAssemblerAssembleHtmlElement(
                    $submittedKey,
                    array(
                        'styles' => array(
                            'background: #333333'
                        ),
                        'classes' => array(
                            'sdm-dev-output-padding-all',
                            'sdm-dev-output-rounded-border',
                        ),
                    )
                ) .
                $sdmassembler->sdmAssemblerAssembleHtmlElement(
                    $submittedValue,
                    array(
                        'styles' => array(
                            'background: #000000'
                        ),
                        'classes' => array(
                            'sdm-dev-output-padding-all',
                            'sdm-dev-output-rounded-border',
                        ),
                    )
                ),
                array(
                    'elementType' => 'li',
                    'styles' => array(
                        'color: palegreen',
                        'font-size: .7em',
                        'background: #33CCFF',
                        'border: 3px solid #33CC66',
                        'border-radius: 5px',
                        'padding: 20px'
                    )
                )
            );
        }
    }

    $output .= '</ul>';

    /* Display app $output */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);


}