<?php

/**
 * Update Content form submission handler for the Content Manager core app.
 */

/** Initialize form */
$sdmForm = new SdmForm();

/* Configure $options array */
$options = array(
    'incpages' => array('contentManagerUpdateContentFormSubmission'),
);

/* Get submitted page name */
$page = $sdmForm->sdmFormGetSubmittedFormValue('page');

/* Make sure the user does not accidently create a page called contentManager. */
if ($page !== 'contentManager') {
    /* Initialize $output var */
    $output = '';

    /* Check if form was submitted successfully. */
    if ($sdmForm->sdmFormGetSubmittedFormValue('content_manager_form_submitted') === 'content_manager_form_submitted') {
        /* Initialize $wrapperStatusHtml. */
        $wrapperStatusHtml = '';
        /* Update each wrapper. */
        foreach ($sdmcms->sdmCmsDetermineAvailableWrappers() as $displayValue => $machineValue) {
            /* Update the wrapper in the DataObject. */
            $sdmcms->sdmCmsUpdateContent($page, $machineValue, nl2br($sdmForm->sdmFormGetSubmittedFormValue($machineValue)));

            /* Get html that will be used along with the rest of the app $output for each wrapper. */
            $wrapperStatusHtml .= '<div class="border-rounded padded-15 highlight">';
            $wrapperStatusHtml .= '<p>wrapper name: "' . $displayValue . '"';
            $wrapperStatusHtml .= '<p>wrapper id: "' . $machineValue . '"</p>';
            $wrapperStatusHtml .= '<p>wrapper content:';
            $wrapperStatusHtml .= ($sdmForm->sdmFormGetSubmittedFormValue($machineValue) === '' ? ' This wrapper has no content.</p>' : '</p><div class="border-rounded padded-15 border-dotted">' . $sdmForm->sdmFormGetSubmittedFormValue($machineValue) . '</p></div>');
            $wrapperStatusHtml .= '</div>';
        }

        /* App $output for successful update. */
        $output .= '<!-- contentManager div -->';
        $output .= '<div id="contentManager">';
        $output .= '<p>Page updated successfully.</p>';
        $output .= '<p>Go to <a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '?page=' . $page . '">' . $page . '</a></p>';
        $output .= '<p>Overview of content wrappers for this page:</p>';
        $output .= $wrapperStatusHtml;
        $output .= '</div><!-- close contentManager div -->';
    } else {
        /* App $output if form could not be submitted. */
        $output .= '<div id="contentManager"><p>And error occurred and the form could not be submitted</p></div>';
    }
} else {
    /* App $output if user tried to create a page called "contentManager". */
    $output = '<p>Sorry, you cannot create a page with the name "contentManager" for security reasons.</p>';
}

/* Incorporate app $output. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);