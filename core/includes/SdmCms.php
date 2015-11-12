<?php

/**
 * The <b>SdmCms</b> is responsible for provideing the components necessary for
 * content management.
 *
 * @author Sevi Donnelly Foreman
 */
class SdmCms extends SdmCore {

    /**
     * <p>Creates content for a specific page ($page) and content wrapper ($id)</p>
     * <p><b>Warning: This method will overwrite content if it already exists.
     * @TODO we may need to split the update and add processes into 2 methods to prevent
     * overwriting of content. Though at the moment the contentManager app does this check for
     * us but it could make it hard when developing other content manager like apps to sperate
     * add and update if they arent seperate in thsi core class.</b></p>
     * @param string $page <p>The <b>name</b> of the page this content belongs to.</p>
     * @param string $id <p>Machine safe string that correlates to the css id associated
     * with the div used in the <i>current themes</i> page.php to display this content.
     * <br>i.e., A piece of content with an <b><i>$id</i></b> set to <b>'main_content'</b> will correlate
     * to a div with an <b><i>id</i></b> of <b>'main_content'</b> in the current themes page.php and that div
     * will display the content with an <b><i>$id</i></b> of <b>'main_content'</b></p>
     * @param string $html <p>The html for this content.</p>
     * @return int The number of bytes written to data.json or the DB. Returns false on failure.
     */
    public function sdmCmsUpdateContent($page, $id, $html) {
        $content = $this->sdmCoreGetDataObject();
        // filter out problematic charaters from $html and insure UTF-8 using iconv()
        $filteredHtml = iconv("UTF-8", "UTF-8//IGNORE", $html);
        $filteredHtml2 = iconv("UTF-8", "ISO-8859-1//IGNORE", $filteredHtml);
        $filteredHtml3 = iconv("ISO-8859-1", "UTF-8", $filteredHtml2);
        // if the page does not already exist in CORE create a placeholder object for it
        if (!isset($content->content->$page) === true) {
            $content->content->$page = new stdClass();
        }
        $content->content->$page->$id = htmlentities(utf8_encode(trim($filteredHtml3)), ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5, 'UTF-8');
        $data = json_encode($content);
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $data, LOCK_EX);
    }

    /**
     * <p>Determines available content wrappers for current theme by looking in the <i>current theme's</i> page.php file.</p>
     * @return array An array formated key => value where key is formated for display, and value is formated to be <b>code-safe</b>
     * <p>i.e. the requried "main_content" wrapper would be returned in an array formated as follows<br/><br/>
     * <b>array("Main Content" => 'main_content')</b></p>
     */
    public function sdmCmsDetermineAvailableWrappers() {
        $html = file_get_contents($this->sdmCoreGetCurrentThemeDirectoryPath() . '/page.php');
        $dom = new DOMDocument();
        // for now we are surpressing any errors thrown by loadHTML() because it complains when malformed xml and html is loaded, and the errors were clogging up the error log during other development branches. Howver it is very important that a fix is found for this issue as it could lead to unknown bugs.
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
     * <p>Loads a specific piece of content.</p>
     * @param string $page <p>The page we want to load content from.</p>
     * @param string $contentWrapper <p>The content wrapper we want to load content from.</p>
     * @return string <p>The string of html for this $contentWrapper.</p>
     */
    public function sdmCmsLoadSpecificContent($page = 'homepage', $contentWrapper = 'main_content') {
        // load our json data from data.json and convert into an array
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
    public function sdmCmsDetermineAvailableThemes() {
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
    public function sdmCmsChangeTheme($theme) {
        $data = $this->sdmCoreGetDataObject();
        $data->settings->theme = $theme;
        $jsondata = json_encode($data);
        return file_put_contents($this->sdmCoreGetDataDirectoryPath() . '/data.json', $jsondata, LOCK_EX);
    }

    /**
     * Deletes a page.
     * @param string $pagename Name of the page to delete.
     */
    public function sdmCmsDeletePage($pagename) {
        $data = $this->sdmCoreGetDataObject();
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
    public function sdmCmsDetermineAvailableApps() {
        $userApps = $this->sdmCoreGetDirectoryListing('', 'userapps');
        $coreApps = $this->sdmCoreGetDirectoryListing('', 'coreapps');
        $apps = array_merge($userApps, $coreApps);

        // we dont want to list directories that are not apps
        $ignore = array('.DS_Store', '.', '..');
        foreach ($apps as $app) {
            if (!in_array($app, $ignore)) {
                $available_apps[ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $app))] = $app;
            }
        }
        return $available_apps;
    }

    /**
     * Switches an app from on to off.
     * @param string $app <p>Name of the app to switch on or of.</p>
     * @param string $state  <p>State of the app, either on or off.</p>
     * @return bool true on sucessful state change, false if unable to switch state.
     */
    public function sdmCmsSwitchAppState($app, $state) {
        $data = $this->sdmCoreGetDataObject();
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
