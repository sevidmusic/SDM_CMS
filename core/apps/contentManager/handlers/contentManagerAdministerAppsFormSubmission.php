<?php

$ouptut = '';
// form submitted successfully
if ($_POST['sdm_form']['content_manager_form_submitted'] === 'content_manager_form_submitted') {
    //$sdmcore->sdm_read_array($_POST['sdm_form']);
    foreach ($sdmcms->sdmCmsDetermineAvailableApps() as $app) {
        $sdmcms->sdmCmsSwitchAppState($app, $_POST['sdm_form'][$app]);
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

$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options);