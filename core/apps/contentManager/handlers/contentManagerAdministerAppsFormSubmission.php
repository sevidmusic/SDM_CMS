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
        /* Initialize $switch array */
        $switch = array();

        /* Loop through available apps updating state if necessary. */
        foreach ($availableApps as $appname => $app) {
            /* Determine $app's dependencies. */
            $dependencies = $sdmcms->sdmCmsDetermineAppDependencies($app);

            /* Determine the state to switch the $app to. */
            $newAppState = SdmForm::sdmFormGetSubmittedFormValue($app);

            /* Switch app state to $newAppState and push returned data to $switch array. */
            array_push($switch, $sdmcms->sdmCmsSwitchAppState($app, $newAppState));
        }

        /* Begin building display. */
        $enabledAppsDisplay = '<h4>The following apps were enabled:</h4>';
        $enabledDependenciesDisplay = '<h4>The following apps were enabled because they are
                                depended on by other apps:</h4>';
        $disabledAppsDisplay = '<h4>The following apps were disabled:</h4>';

        /* Initialize alreadyDisplayed array. */
        $alreadyDisplayed = array('enabledApps' => array(), 'enabledDependencies' => array(), 'disabledApps' => array());

        /* Finish building display for the $switch array. */
        foreach ($switch as $appState) {
            /* Enabled apps. */
            foreach ($appState['enabledApps'] as $enabledApp) {
                /* As long as $enabledApp is not already registered in the $alreadyDisplayed array
                   add $enabledApp to $enabledAppsDisplay. */
                if (!in_array($enabledApp, $appState['enabledDependencies']) && !in_array($enabledApp, $alreadyDisplayed['enabledApps']) && !property_exists($initiallyEnabledApps, $enabledApp)) {
                    $enabledAppsDisplay .= '<div class="cm-app-enabled"><a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $enabledApp . '">' . $enabledApp . '</a></div>';
                }
                /* Register app in $alreadyDisplayed['enabledApps'] array. */
                array_push($alreadyDisplayed['enabledApps'], $enabledApp);
            }

            /* Enabled dependencies. */
            foreach ($appState['enabledDependencies'] as $enabledDependency) {
                /* As long as $enabledDependency is not already registered in the $alreadyDisplayed array
                   add $enabledDependency to $enabledAppsDisplay. */
                if (!in_array($enabledDependency, $alreadyDisplayed['enabledDependencies']) && !property_exists($initiallyEnabledApps, $enabledDependency)) {
                    $enabledDependenciesDisplay .= '<div class="cm-dependency-enabled"><a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $enabledDependency . '">' . $enabledDependency . '</a></div>';
                }
                /* Register app in $alreadyDisplayed['enabledApps'] array. */
                array_push($alreadyDisplayed['enabledDependencies'], $enabledDependency);
            }

            /* Disabled apps. */
            foreach ($appState['disabledApps'] as $disabledApp) {
                /* As long as $disabledApp is not already registered in the $alreadyDisplayed array
                   add $disabledApps to $enabledAppsDisplay. */
                if (!in_array($disabledApp, $alreadyDisplayed['disabledApps']) && property_exists($initiallyEnabledApps, $disabledApp)) {
                    $disabledAppsDisplay .= '<div class="cm-app-disabled">' . $disabledApp . '</div>';
                }
                /* Register app in $alreadyDisplayed['disabledApps'] array. */
                array_push($alreadyDisplayed['disabledApps'], $disabledApp);
            }
        }

        $output .= $enabledAppsDisplay . $enabledDependenciesDisplay . $disabledAppsDisplay;
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