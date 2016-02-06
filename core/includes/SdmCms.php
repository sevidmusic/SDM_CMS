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
     * @TODO: It may be benificial to split the update and add logic into 2 seperate methods.
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
     * @return bool True if update was succsessfull, or false on failure.
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

        /* Determine weather the update succeeded or failed.*/
        $status = ($update < 0 || $update !== false ? true : false);

        /**/
        return $status;
    }

    /**
     * Determines available content wrappers for current theme by looking in the current theme's page.php file.
     *
     * @return array An array formatted key => value where key is formatted for display, and value is formatted to be
     * code-safe.
     *
     * i.e. the 'main_content' wrapper would be returned in an array formatted as follows:
     *
     * array("Main Content" => 'main_content')
     */
    public function sdmCmsDetermineAvailableWrappers()
    {
        $html = file_get_contents($this->sdmCoreGetCurrentThemeDirectoryPath() . '/page.php');
        $dom = new DOMDocument();
        /* For now we are suppressing any errors thrown by loadHTML() because it complains
         when malformed xml and html is loaded, and the errors were clogging up the error
         log during other development branches. However it is very important that a fix is
         found for this issue as it could lead to unknown bugs. */
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $tags = $xpath->query('//div[@id]');
        $data = array();
        foreach ($tags as $tag) {
            if (substr(trim($tag->getAttribute('id')), 0, 6) != 'locked') {
                $data[ucwords(str_replace(array('-', '_'), ' ', trim($tag->getAttribute('id'))))] = trim($tag->getAttribute('id'));
            }
        }
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
        /* load our json data from data.json and convert into an array */
        $data = json_decode(file_get_contents($this->sdmCoreGetCoreDirectoryPath() . '/sdm/data.json'), true);
        return $data['content'][$page][$contentWrapper]; // @TODO : Use object notation instead of array notation
    }

    /**
     * <p>Determines what themes are available themes, and
     * returns them in an array where the KEYS are formatted
     * for display and the VALUES are formatted for use in code.</p>
     * @return array <p>Array of available themes.
     * <br/>
     * <br/>
     * i.e., array('Some Theme' => 'someTheme')
     * </p>
     */
    public function sdmCmsDetermineAvailableThemes()
    {
        $themes = $this->sdmCoreGetDirectoryListing('', 'themes');
        // we dont want to list directories that are not themes
        $ignore = array('.DS_Store', '.', '..');
        foreach ($themes as $theme) {
            if (!in_array($theme, $ignore)) {
                $availableThemes[ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $theme))] = $theme;
            }
        }
        return $availableThemes;
    }

    /**
     * <p>Changes the sites theme.</p>
     * @param string $theme <p>The desired theme</p>
     * @return int The number of bytes written to data.json or the DB. Returns false on failure.
     */
    public function sdmCmsChangeTheme($theme)
    {
        $data = $this->sdmCoreLoadDataObject(false);
        $data->settings->theme = $theme;
        $jsondata = json_encode($data);
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $jsondata, LOCK_EX);
    }

    /**
     * Deletes a page.
     * @param string $pagename Name of the page to delete.
     */
    public function sdmCmsDeletePage($pagename)
    {
        $data = $this->sdmCoreLoadDataObject(false);
        unset($data->content->$pagename);
        $jsondata = json_encode($data);
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $jsondata, LOCK_EX);
    }

    /**
     * <p>Determines what apps <i>(core and user)</i> are available, and
     * returns them in an array where the KEYS are formatted
     * for display and the VALUES are formatted for use in code.</p>
     * @return array <p>Array of available apps.
     * <br/>
     * <br/>
     * i.e., array('Some App' => 'someApp')
     * </p>
     */
    public function sdmCmsDetermineAvailableApps()
    {
        $userApps = $this->sdmCoreGetDirectoryListing('', 'userapps');
        $coreApps = $this->sdmCoreGetDirectoryListing('', 'coreapps');
        $apps = array_merge($userApps, $coreApps);

        // we dont want to list directories that are not apps
        $ignore = array('.DS_Store', '.', '..');
        foreach ($apps as $app) {
            if (!in_array($app, $ignore)) {
                $availableApps[ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $app))] = $app;
            }
        }
        return $availableApps;
    }

    /**
     * Switches an app from on to off.
     * @param string $app <p>Name of the app to switch on or of.</p>
     * @param string $state <p>State of the app, either on or off.</p>
     * @return bool true on sucessful state change, false if unable to switch state.
     */
    public function sdmCmsSwitchAppState($app, $state)
    {
        $data = $this->sdmCoreLoadDataObject(false);
        $enabledApps = $data->settings->enabledapps;
        switch ($state) {
            case 'on':
                // As long as the app is not already enabled, enable it. No need to enable an already enabled app, and doing so could cause bugs as it might clutter the enabledApps array with duplicate values.
                if (!property_exists($enabledApps, $app)) {
                    $enabledApps->$app = $app;
                }
                break;
            case 'off':
                // We only need to remove the app from the enabled apps object if it already exists as a property. No need to tamper with our data if the app being disabled is already excluded from the enabled apps array.
                if (property_exists($enabledApps, $app)) {
                    unset($enabledApps->$app);
                }
                break;
        }
        // unset old enabledapps object
        unset($data->settings->enabledapps);
        // create new enabledapps object
        $data->settings->enabledapps = $enabledApps;
        //$this->sdmCoreSdmReadArray(array('DATA OBJECT TO BE SAVED' => $data));
        $jsondata = json_encode($data);
        return (file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $jsondata, LOCK_EX) > 0 ? true : false);
    }

}
