<?php

// initialize a form object so we can get the last forms sumbitted values
$sdmForm = new SdmForm();
$output = '';
// form submitted successfully
if ($sdmForm->sdmFormGetSubmittedFormValue('content_manager_form_submitted') === 'content_manager_form_submitted') {
    $sdmcms->sdmCmsDeletePage($sdmForm->sdmFormGetSubmittedFormValue('page_to_delete'));


    $output .= '
                    <!-- contentManager div -->
                    <div id"contentManager">
                        <p>Form has been submitted with the following values.
                            <ul>
                                <li>PAGE : ' . $sdmForm->sdmFormGetSubmittedFormValue('page_to_delete') . '</li>
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

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);