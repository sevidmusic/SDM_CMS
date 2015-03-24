<?php

$output = '';
// form submitted successfully
if (SdmForm::sdmFormGetSubmittedFormValue('content_manager_form_submitted') === 'content_manager_form_submitted') {
    //$sdmcore->sdmCoreSdmReadArray($_POST['SdmForm']);
    foreach ($sdmcms->sdmCmsDetermineAvailableApps() as $app) {
        $sdmcms->sdmCmsSwitchAppState($app, SdmForm::sdmFormGetSubmittedFormValue($app));
    }
    //$sdmcms->sdmCmsSwitchAppState('contentManager', 'off');
    $output .= '
                    <!-- contentManager div -->
                    <div id"contentManager">
                        <p>Form has been submitted.</p>
                    </div>
                    <!-- close contentManager div -->';
}
// form submitted but error occured
else {
    $output .= '
                <div id="contentManager">
                    <p>And error occured and the form could not be submitted</p>
                </div>
                ';
}

$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options);