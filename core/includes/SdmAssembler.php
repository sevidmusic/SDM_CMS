<?php

/**
 * The <b>SdmAssembler</b> is responsible for loading and assembleing page content.
 * It is also responsible for loading and assemlbing core and user apps.
 * During the assemlby process content is loaded and passed to apps to give apps
 * a chance to modify content before all the apps and content is finally asembled into
 * html and echoed to the page.
 *
 * @author foremase
 */
class SdmAssembler extends SdmCore {

    private static $Initialized;

    /**
     * Initializes a single instance of the SdmAssembler class.
     * @return object <p>An SdmAssembler Object</p>
     */
    public static function sdmInitializeAssembler() {
        if (!isset(self::$Initialized)) {
            self::$Initialized = new SdmAssembler;
        }
        return self::$Initialized;
    }

    /**
     * <p>Returns the HTML header for the page as a string. The SdmAssembler will give apps
     * a chance to modify the header prior to returning it.</p>
     * @return string <p>The HTML header for the page.</p>
     */
    public function assembleHtmlHeader() {
        return '<!DOCTYPE html>
            <html>
                <head>
                    <title>' . (isset($_GET['page']) ? ucfirst($_GET['page']) : ucfirst('homepage')) . '</title>
                    <base href="' . $this->getRootDirectoryUrl() . '" target="_self">
                    <meta name="description" content="Website powered by the SDM CMS">
                    <meta name="author" content="Sevi Donnelly Foreman">
                    <meta http-equiv="refresh" content="3000">
                    <script src="' . $this->getCoreDirectoryUrl() . '/js/jquery-1.9.0/jquery.min.js"></script>
                    <script src="' . $this->getCoreDirectoryUrl() . '/js/jquery-ui-1.11.2/jquery-ui.js"></script>
                    <link rel="stylesheet" href="' . $this->getCurrentThemeDirectoryUrl() . '/sdm_layout.css">
                    <!-- DEV JS TO TEST JQUERY AND JQERU UI ARE WORKING | REMOVE ONCE OUT OF DEV -->
                    <script>
                    if (typeof jQuery != "undefined") {
                        //alert("jQuery Loaded Sucessfully");
                        // then check if jQuery UI has been loaded
                        if (typeof jQuery.ui != "undefined") {
                            //alert("jQuery UI Loaded Sucessfully");
                        }
                        else { // if jQuery UI is missing, alert user
                            alert("MISSING JS RESOURCE:\n\n jQuery UI failed to load properly so some site features may not be available.\n\nPlease report this to the site admin at:\n\nADMINEMAILADDRESS@EMAILSERVER.COM\n\nor at\n\nLINKTOADMINCONTACT.");
                        }
                    } else { // if jQuery is missing alert user
                            alert("MISSING JS RESOURCE:\n\n jQuery failed to load properly so some site features may not be available.\n\nPlease report this to the site admin at:\n\nADMINEMAILADDRESS@EMAILSERVER.COM\n\nor at\n\nLINKTOADMINCONTACT.");
                    }
                    </script>
                    <!-- END DEV JS | REMOVE ONCE OUT OF DEV-->
                </head>
            <body class="' . $this->determineCurrentTheme() . '">';
    }

    /**
     * <p>Loads and assembles a content object for the requested page.</p>
     * @return object A content object for the requested page.
     */
    public function loadAndAssembleContentObject() {
        $page = $this->determineRequestedPage();
        // end dev content creation
        $sdmassembler_contentObject = $this->sdmCoreLoadDataObject();
        // load and assemble apps
        $this->loadCoreApps($sdmassembler_contentObject);
        // make sure content exists, if it does return it, if not, print a content not found message
        switch (isset($sdmassembler_contentObject->content->$page)) {
            case TRUE:
                //var_dump($sdmassembler_contentObject->content->$page);
                $sdmassembler_contentObject = $this->preparePageForDisplay($sdmassembler_contentObject->content->$page);
                return $sdmassembler_contentObject;
                break;
            default:
                return json_decode(json_encode(array('main_content' => '<p>The requested content could not be found. Check the url to for typos. If error persists and your sure this content should exist contact the site admin to report the error.</p>')));
        }
    }

    /**
     * <p>Prepares the $page for display in a theme. Basically, when
     * a page is created it's content is filtered to insure no bad
     * chars are included and that the encoding is UTF-8.
     * In order to insure html tags are interpreted as html and we need to
     * reverse some of the filtering that was done on the page data.</p>
     * <p>This method should only be used internally by the SdmAssembler and should be kept <i>private</i>.</p>
     * @param object $page <p>The page object to prepare.</p>
     * @return object <p>The prepared page object.</p>
     *
     */
    private function preparePageForDisplay($page) {
        foreach ($page as $name => $value) {
            $page->$name = html_entity_decode($value, ENT_HTML5, 'UTF-8');
        }
        return $page;
    }

    /**
     * Loads enabled core apps.
     * @todo Change name to loadApps, more accurate description b/c this method is responsible for
     * loading all apps
     * @param object $sdmassembler_contentObject <p>The Content object for the requested page.</p>
     */
    private function loadCoreApps($sdmassembler_contentObject) {
        // store parent (i.e. SdmCore) in an appropriatly named var to give apps easy access
        $sdmcore = new parent;
        // store object in an appropriatly named var to give apps easy access
        $sdmassembler = $this;
        // store requested page (determined by core) in an appropriatly named var to give apps easy access
        $sdmassembler_requestedpage = $this->determineRequestedPage();
        // store requested page (determined by core) in an appropriatly named var to give apps easy access
        $sdmassembler_contentObject = $sdmassembler_contentObject;
        $settings = $sdmcore->sdmCoreLoadDataObject()->settings;
        $coreapps = $sdmcore->sdmCoreGetDirectoryListing('', 'coreapps');
        $userapps = $sdmcore->sdmCoreGetDirectoryListing('', 'userapps');
        $apps = array();
        foreach ($coreapps as $value) {
            if ($value != '.' && $value != '..' && $value != '.DS_Store') {
                $apps[] = $value;
            }
        }
        foreach ($userapps as $value) {
            if ($value != '.' && $value != '..' && $value != '.DS_Store') {
                $apps[] = $value;
            }
        }
        foreach ($apps as $app) {
            if (property_exists($settings->enabledapps, $app)) {
                // load apps
                if (file_exists($this->getCoreAppDirectoryPath() . '/' . $app . '/' . $app . '.php')) {
                    require_once($this->getCoreAppDirectoryPath() . '/' . $app . '/' . $app . '.php');
                } else if (file_exists($this->getUserAppDirectoryPath() . '/' . $app . '/' . $app . '.php')) {
                    require($this->getUserAppDirectoryPath() . '/' . $app . '/' . $app . '.php');
                } else {
                    echo '<!-- site has no enabled apps -->';
                }
            }
        }
    }

    /**
     * Returns <p>The required closing HTML tags for the page.</p>
     * @return <p>string The required HTML closing tags as a string.</p>
     */
    public function assembleHtmlRequiredClosingTags() {
        return '
    <!--This site was built using the SDM CMS content management system which was designed and developed by Sevi Donnelly Foreman in the year 2014.-->
    <!--To contact the developer of the SDM CMS write to sdmwebsdm@gmail.com.-->
    <!--Note: Sevi is not necessarily the author of this site, he is just the developer of Content Management System that is used to build/maintain this site.-->
    </body>
    </html>
    ';
    }

}

/**
 * Still need the following methods:
 * assembleNavigation() : in a simialr way to how apps are
 *                      added to the page, navigation objects
 *                      stored in our JOSN or DB will need to be
 *                      added to the page. The assembleNavigation()
 *                      method will be resposibile for this task.
 *
 */