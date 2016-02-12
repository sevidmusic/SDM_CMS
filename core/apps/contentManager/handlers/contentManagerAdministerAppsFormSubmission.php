<?php

/**
 * Administer Apps form submission handler for the Content Manager core app.
 */

/* Initialize app $output. */
$output = '';

/* Tracks enabled apps. */
$on = array();

/* Tracks disabled apps. */
$off = array();

/* Determine currently available apps. */
$availableApps = $sdmcms->sdmCmsDetermineAvailableApps();

/* Determine currently enabled apps. */
$initiallyEnabledApps = $sdmcms->sdmCoreDetermineEnabledApps();

/* Check if form was submitted successfully. */
switch (SdmForm::sdmFormGetSubmittedFormValue('content_manager_form_submitted')) {
    case 'content_manager_form_submitted':
        /* Loop through available apps updating state if necessary. */
        foreach ($availableApps as $appname => $app) {
            /* Determine $app's dependencies. Apps this $app is dependent on
             will be enabled internally, but it's still nice to communicate
             to the user all apps that have been enabled/disabled, including
             apps this $app is dependent on. */
            $dependencies = $sdmcms->sdmCmsDetermineAppDependencies($app);

            /* Determine the state to switch the $app to. */
            $newAppState = SdmForm::sdmFormGetSubmittedFormValue($app);

            /* Switch app state to $newAppState */
            $sdmcms->sdmCmsSwitchAppState($app, $newAppState);

            /* If $app was enabled add it to the $on array. */
            if ($newAppState === 'on') {
                /* As long as $app was not already enabled add it to the $on array */
                if (!property_exists($initiallyEnabledApps, $app) === true) {
                    $on[] = $app;
                }

                /*  */
                foreach ($dependencies as $dependency) {
                    $requiredApps[$dependency][] = $app;
                }
            }

        }
        $output .= '<h4>The following apps were enabled:</h4><ul>';
        foreach ($on as $enabledApp) {
            $output .= '<li style="color:#00C957;">' . $enabledApp . '</li>';
        }
        $output .= '</ul>';

        $output .= '<h4>The following apps were enabled because they are required by another enabled app:</h4><ul>';
        foreach ($requiredApps as $requiredApp => $dependentApps) {
            $output .= '<li style="color:#00C957;">' . $requiredApp . ' <span style="color: #ffffff;">(Dependent Apps : ';
            $numDepApps = count($dependentApps);
            foreach ($dependentApps as $depKey => $dependentApp) {
                $output .= $dependentApp . ($depKey === $numDepApps - 1 ? '' : ', ');
            }
            $output .= ' )</span></li>';
        }
        $output .= '</ul>';
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