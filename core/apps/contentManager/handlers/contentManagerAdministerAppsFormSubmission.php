<?php

/**
 * Administer Apps form submission handler for the Content Manager core app.
 */

/* Initialize app $output. */
$output = '';

/* Tracks enabled apps. */
$on = null;

/* Tracks disabled apps. */
$off = array();

/* Determine currently available apps. */
$availableApps = $sdmcms->sdmCmsDetermineAvailableApps();

/* Check if form was submitted successfully. */
switch(SdmForm::sdmFormGetSubmittedFormValue('content_manager_form_submitted')){
    case 'content_manager_form_submitted':
        /* Loop through available apps updating state if necessary. */
        foreach ($availableApps as $appname => $app) {
            $newAppState = SdmForm::sdmFormGetSubmittedFormValue($app);
            $sdmcms->sdmCmsSwitchAppState($app, $newAppState);
            $on = $sdmcms->sdmCoreDetermineEnabledApps();
            if ($newAppState !== 'on') {
                $off[0] = '<span style="color:red;">Reworking the display of disabled apps.!</span>';
            }
        }
        $output .= '<h4>The following apps are enabled:</h4><ul>';
        foreach ($on as $enabledApp) {
            $output .= '<li style="color:#00C957;">' . $enabledApp . '</li>';
        }
        $output .= '</ul>';
        $output .= '<h4>The following apps are disabled:</h4><ul>';
        foreach ($off as $disabledApp) {
            $output .= '<li style="color:#00BFFF;">' . $disabledApp . '</li>';
        }
        $output .= '</ul>';
        $output .= '<!-- contentManager div -->
                <div id"contentManager">
                  <p>Form has been submitted.</p>
                </div>
                <!-- close contentManager div -->';
        break;
    default:
        $output .= '
                <div id="contentManager">
                    <p>And error occurred and the form could not be submitted</p>
                </div>
                ';
        break;
}

/* Incorporate app output. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);