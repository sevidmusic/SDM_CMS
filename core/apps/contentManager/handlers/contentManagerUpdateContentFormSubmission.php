<?php

/**
 * Update Content form submission handler for the Content Manager core app.
 */

/** initialize form */
$sdmForm = new SdmForm();
$options = array(
    'incpages' => array('contentManagerUpdateContentFormSubmission'),
);
if ($sdmForm->sdmFormGetSubmittedFormValue('page') !== 'contentManager') {
    $output = '';
    // form submitted successfully
    if ($sdmForm->sdmFormGetSubmittedFormValue('content_manager_form_submitted') === 'content_manager_form_submitted') {
        $output .= '
                    <!-- contentManager div -->
                    <div id"contentManager">
                        <p>Form has been submitted with the following values.
                            <ul>
                                <li>PAGE : <a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '?page=' . $sdmForm->sdmFormGetSubmittedFormValue('page') . '">' . $sdmForm->sdmFormGetSubmittedFormValue('page') . '</a></li>';
        // loop through and update wrappers
        foreach ($sdmcms->sdmCmsDetermineAvailableWrappers() as $dispalyValue => $machineValue) {
            $sdmcms->sdmCmsUpdateContent($sdmForm->sdmFormGetSubmittedFormValue('page'), $machineValue, nl2br($sdmForm->sdmFormGetSubmittedFormValue($machineValue)));
            $output .= '<li>Wrapper : "' . $dispalyValue . '" (<i>' . $machineValue . '</i>)</li><li>Wrapper Content : <xmp>' . $sdmForm->sdmFormGetSubmittedFormValue($machineValue) . '</xmp></li>';
        }
        $output .= '
                            </ul></p>
                    </div>
                    <!-- close contentManager div -->';
    } // form submitted but error occured
    else {
        $output .= '
                <div id="contentManager">
                    <p>And error occured and the form could not be submitted</p>
                </div>
                ';
    }
} else {
    $output = '<p>Page could not be created because pages cannot use the name "contentManager" for security reasons. You can however add a page for one of the content managers stages.</p>';
}
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);