<?php

/**
 * The SdmCms is responsible for providing the components necessary for
 * content management.
 *
 * @author Sevi Donnelly Foreman
 */
class SdmCms extends SdmCore
{

    /**
     * Updates or creates a page.
     *
     * This method uses PHP's iconv() and utf8_encode() to insure $html is UTF-8 encoded prior to
     * storage. This method also uses PHP's htmlentities() to convert all applicable characters
     * in $html to HTML entities prior to storage.
     *
     * Warning: This method will overwrite content if it already exists.
     *
     * @todo: It may be beneficial to split the update and add logic into 2 separate methods to
     *        prevent accidental overwriting of pages that already exist. The other option
     *        would be to create a parameter that dictates weather or not pages
     *        that already exist should be overwritten. One final option would be to
     *        create a method that called sdmCmsAddContent() that utilizes sdmCmsUpdateContent()
     *        but performs a check to make sure existing pages are not overwritten.
     *
     * @param string $page The name of the page this content belongs to.
     *
     * @param string $wrapper The wrapper this content belongs in. (e.g., 'main_content')
     *
     * @param string $html The content's html. Note: This parameter will be filtered internally
     *               via PHP's iconv() and utf8_encode() to insure UTF-8. Also, this parameter will be
     *               filtered internally via htmlentities() to insure all applicable characters
     *               are converted to HTML entities prior to storage.
     *
     * @return bool True if update was successful, or false on failure.
     */
    public function sdmCmsUpdateContent($page, $wrapper, $html)
    {
        /* Load the entire data object */
        $dataObject = $this->sdmCoreLoadDataObject(false);

        /* Filter $html to insure encoding is UTF-8. */
        $filteredHtml = iconv("UTF-8", "UTF-8//IGNORE", $html);
        $filteredHtml2 = iconv("UTF-8", "ISO-8859-1//IGNORE", $filteredHtml);
        $filteredHtml3 = iconv("ISO-8859-1", "UTF-8", $filteredHtml2);
        $utf8Html = utf8_encode(trim($filteredHtml3));

        /* If the page does not already exist in the DataObject create a placeholder object for it. */
        if (!isset($dataObject->content->$page) === true) {
            $dataObject->content->$page = new stdClass();
        }

        /* Convert all applicable characters in $html to HTML entities. */
        $dataObject->content->$page->$wrapper = htmlentities($utf8Html, ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5, 'UTF-8');

        /* Encode the updated dataObject as json to prepare for storage. */
        $data = json_encode($dataObject);

        /* Store the updates. */
        $update = file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $data, LOCK_EX);

        /* Determine weather the update succeeded or failed. */
        $status = ($update < 0 && $update !== false ? true : false);

        /* Return true if update succeeded, or false if update failed. */
        return $status;
    }

    /**
     * Returns an array of available content wrappers for the current theme.
     *
     * Note: only content wrappers whose names do not begin with the special value 'locked'
     * will be included in the returned array.
     *
     * The names of the content wrappers are used as keys and values. Keys are formatted for display
     * and values are formatted for use in code.
     *
     * e.g.,
     *
     * // For a theme with 2 wrappers, 'site-logo' and 'main_content', the following array would be returned:
     *
     * array('Site Logo' => 'site-logo', 'Main Content' => 'main_content');
     *
     * Note: Content wrappers whose name begins with "locked" will not be included in the array.
     *
     * @return array An array of content wrapper names for the current theme.
     */
    public function sdmCmsDetermineAvailableWrappers()
    {
        /* Load html from current themes page.php. */
        $html = file_get_contents($this->sdmCoreGetCurrentThemeDirectoryPath() . '/page.php');

        /* Instantiate a new DOMDocument() object. */
        $dom = new DOMDocument();

        /* Load $html into the $dom object. For now we are suppressing any errors thrown by loadHTML()
         because it complains when malformed xml and html is loaded, and the errors were clogging up
         the error log during other development. However it is very important that a fix is
         found for this issue as it could lead to unknown bugs. */
        @$dom->loadHTML($html);

        /* Instantiate new DOMXPath() object and pass it the $dom object. */
        $xpath = new DOMXPath($dom);

        /* Extract all div tags that have an id attribute. */
        $tags = $xpath->query('//div[@id]');

        /* Initialize $data array. This array will store the extracted wrappers. */
        $data = array();

        /* Extract the wrappers from each of the extracted $tags */
        foreach ($tags as $tag) {
            /* As long as the wrapper does not start with the string "locked" add the wrapper to $data array. */
            if (substr(trim($tag->getAttribute('id')), 0, 6) != 'locked') {
                /* Format array so keys are for display, and values for use in code. */
                $data[ucwords(str_replace(array('-', '_'), ' ', trim($tag->getAttribute('id'))))] = trim($tag->getAttribute('id'));
            }
        }

        /* Return the array of available content wrappers. */
        return $data;
    }

    /**
     * Loads a specific piece of content.
     *
     * @param string $page The name of the page whose content we want to load. Defaults to 'homepage'.
     *
     * @param string $contentWrapper The name of the content wrapper we want to load content from. Defaults
     *                               to 'main_content'
     *
     * @return string String of html for the $contentWrapper.
     */
    public function sdmCmsLoadSpecificContent($page = 'homepage', $contentWrapper = 'main_content')
    {
        /* Load the entire DataObject so all pages are accessible */
        $data = $this->sdmCoreLoadDataObject(false);

        /* Return specified $contentWrapper for the specified $page. */
        return $data->content->$page->$contentWrapper;
    }

    /**
     * Returns an array of available themes.
     *
     * The names of the available themes are used as keys and values. Keys are formatted for display
     * and values are formatted for use in code.
     *
     * e.g.,
     *
     * Returned array will look something like:
     *
     * array('Theme 1' => 'theme1', 'Theme 2' => 'theme2')
     *
     * @return array Array of available themes.
     *
     */
    public function sdmCmsDetermineAvailableThemes()
    {
        /* Get a listing of all the themes in the themes directory. */
        $themes = $this->sdmCoreGetDirectoryListing('', 'themes');

        /* Ignore directories that are not themes. */
        $ignore = array('.DS_Store', '.', '..');

        /* Create array of available themes. */
        foreach ($themes as $theme) {
            /* Only add directories that actually are themes. */
            if (!in_array($theme, $ignore)) {
                /* Format array so keys are for display, and values for use in code. */
                $availableThemes[ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $theme))] = $theme;
            }
        }

        /* Return an array of available themes. */
        return $availableThemes;
    }

    /**
     * Changes the sites theme.
     *
     * @param string $theme The desired theme to switch to.
     *
     * @return bool True if site theme was changed successfully, or false on failure.
     */
    public function sdmCmsChangeTheme($theme)
    {
        /* Load the entire data object */
        $dataObject = $this->sdmCoreLoadDataObject(false);

        /* Set theme to $theme. */
        $dataObject->settings->theme = $theme;

        /* Prepare DataObject for storage. */
        $jsonData = json_encode($dataObject);

        /* Attempt to store the updated DataObject. */
        $status = file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $jsonData, LOCK_EX);

        /* Return true if DataObject was written to data.json successfully, or false on failure. */
        return ($status > 0 && $status !== false ? true : false);
    }

    /**
     * Delete a page.
     *
     * @param string $pageName Name of the page to delete.
     *
     * returns
     */
    public function sdmCmsDeletePage($pageName)
    {
        /* Load the entire DataObject in order to be able to access all pages. */
        $data = $this->sdmCoreLoadDataObject(false);

        /* Delete the page from the DataObject. */
        unset($data->content->$pageName);

        /* Prepare the modified DataObject for storage. */
        $jsonData = json_encode($data);

        /* Attempt to store the modified DataObject. */
        $status = file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $jsonData, LOCK_EX);

        /* Return true if page was deleted and DataObject was updated, or false on failure. */
        return ($status > 0 && $status !== false ? true : false);
    }

    /**
     * Returns an array of available core and user apps.
     *
     * The names of the available apps are used as keys and values. Keys are formatted for display
     * and values are formatted for use in code.
     *
     * e.g.,
     *
     * Returned array will look something like:
     *
     * array('Some App' => 'someApp', 'Some Other App' => 'someOtherApp')
     *
     * @return array Array of available apps.
     *
     */
    public function sdmCmsDetermineAvailableApps()
    {
        /* Get a listing of apps in the user apps directory. */
        $userApps = $this->sdmCoreGetDirectoryListing('', 'userapps');

        /* Get a listing of apps in the user apps directory. */
        $coreApps = $this->sdmCoreGetDirectoryListing('', 'coreapps');

        /* Create an initial array of core and user apps. */
        $apps = array_merge($userApps, $coreApps);

        /* Ignore directories that are not apps. */
        $ignore = array('.DS_Store', '.', '..');

        /* Create array of available apps. */
        foreach ($apps as $app) {
            /* Only add directories that actually are apps. */
            if (!in_array($app, $ignore)) {
                /* Format array so keys are for display, and values for use in code. */
                $availableApps[ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $app))] = $app;
            }
        }

        /* Return array of available apps. */
        return $availableApps;
    }

    /**
     * Enable or disable an app.
     *
     * @param string $app Name of the app to enable or disable.
     *
     * @param string $state New state of the app. Determines weather app should be enabled
     *                      or disabled. (options: on, off)
     * @todo: change options for $state to 'enabled' and 'disabled' instead of 'on' and 'off'.
     *
     * @return bool True on successful state change, false if unable to switch state.
     */
    public function sdmCmsSwitchAppState($app, $state)
    {
        $data = $this->sdmCoreLoadDataObject(false);
        $enabledApps = $data->settings->enabledapps;
        switch ($state) {
            /* Enable app */
            case 'on':
                /* As long as the app is not already enabled, enable it. No need to enable an already enabled app,
                  and doing so could cause bugs as it might clutter the enabledApps array with duplicate values. */
                if (!property_exists($enabledApps, $app)) {
                    $enabledApps->$app = $app;
                }
                break;

            /* Disable app */
            case 'off':
                /* We only need to remove the app from the enabled apps object if it already exists as a property.
                 No need to tamper with our data if the app being disabled is already excluded from the enabled
                 apps array. */
                if (property_exists($enabledApps, $app)) {
                    unset($enabledApps->$app);
                }
                break;

            /* Error, invalid value passed to $state */
            default:
                /* If $state not equal to 'on' or 'off' return false, and log an error. */
                $msg = 'Invalid $state "' . $state . '" passed to sdmCmsSwitchAppState(), unable to switch state of app "' . $app . '"';
                error_log($msg);
                return false;
        }

        /* Unset old enabledapps object. */
        unset($data->settings->enabledapps);

        /* Create new enabledapps object. */
        $data->settings->enabledapps = $enabledApps;

        /* Prepare updated DataObject for storage. */
        $jsonData = json_encode($data);

        /* Return true if updated DataObject was stored successfully, or false on failure. */
        return (file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $jsonData, LOCK_EX) > 0 ? true : false);
    }
}
