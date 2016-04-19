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

    /** App $output **/

    /* include test forms */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmDevOutput/devForms/devForm1.php');

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
    if (isset($submittedValuesDisplay) === true) {
        $output .= $submittedValuesDisplay;
    }

    /* Output $customFormHtml */
    $output .= $customForm->sdmFormOpenForm() . $customFormDisplayContainer . $customForm->sdmFormCloseForm();

    /* Display app $output */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);


}