<?php

$output = '';
// form submitted successfully
if (SdmForm::sdmFormGetSubmittedFormValue('content_manager_form_submitted') === 'content_manager_form_submitted') {
    $output .= '
                    <!-- contentManager div -->
                    <div id="contentManager" style="background:#DDDDDD;width:75%;border:2px solid #CCCCCC;border-radius:7px;margin:0 auto;padding:20px;">
                        <p>Form has been submitted with the following values.
                            <ul>
                                <li>THEME: ' . SdmForm::sdmFormGetSubmittedFormValue('theme') . '</li>';
    $output .= '
                            </ul></p>
                    </div>
                    <!-- close contentManager div -->';
    // change the theme
    $sdmcms->sdmCmsChangeTheme(SdmForm::sdmFormGetSubmittedFormValue('theme'));
} // form submitted but error occured
else {
    $output .= '
                <div id="contentManager">
                    <p>And error occure and the form could not be submitted</p>
                </div>
                ';
}

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);