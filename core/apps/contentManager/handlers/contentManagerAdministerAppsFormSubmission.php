<?php

/**
 * Administer Apps form submission handler for the Content Manager core app.
 */

/* Initialize app $output. */
$output = '';

/* Determine currently available apps. */
$availableApps = $sdmcms->sdmCmsDetermineAvailableApps();

/* Determine currently enabled apps. */
$initiallyEnabledApps = $sdmcms->sdmCoreDetermineEnabledApps();

/* Check if form was submitted successfully. */
switch (SdmForm::sdmFormGetSubmittedFormValue('content_manager_form_submitted')) {
    case 'content_manager_form_submitted':
        /* Loop through available apps updating state if necessary. */
        foreach ($availableApps as $appname => $app) {
            /* Determine $app's dependencies. */
            $dependencies = $sdmcms->sdmCmsDetermineAppDependencies($app);

            /* Initialize $enabledDependencies array. */
            $enabledDependencies = array();

            /* Determine the state to switch the $app to. */
            $newAppState = SdmForm::sdmFormGetSubmittedFormValue($app);

            /* Switch app state to $newAppState. */
            $switch = $sdmcms->sdmCmsSwitchAppState($app, $newAppState);

            /* DEV */
            $sdmcms->sdmCoreSdmReadArray(['$app' => $app, '$newAppState' => $newAppState,'$switch' =>$switch]);
        }

        break;

    default:
        $output .= '
                <div class="cm-error">
                    <p>And error occurred and the form could not be submitted</p>
                </div>
                ';
        break;
}

/* Incorporate app output. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);