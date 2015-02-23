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
        // load our data object
        $sdmassembler_dataObject = $this->sdmCoreLoadDataObject();
        // load and assemble apps
        $this->loadCoreApps($sdmassembler_dataObject);
        // make sure content exists, if it does return it, if not, print a content not found message
        switch (isset($sdmassembler_dataObject->content->$page)) {
            case TRUE:
                //var_dump($sdmassembler_dataObject->content->$page);
                $sdmassembler_dataObject = $this->preparePageForDisplay($sdmassembler_dataObject->content->$page);
                return $sdmassembler_dataObject;
                break;
            default:
                return json_decode(json_encode(array('main_content' => '<p>The requested content could not be found. Check the url to for typos. If error persists and your sure this content should exist contact the site admin to report the error.</p>')));
        }
    }

    /**
     * <p>Prepares the $page for display in a theme. Basically, when
     * a page is created it's content is filtered to insure no bad
     * chars are included and that the encoding is UTF-8.
     * In order to insure html tags are interpreted as html we need to
     * reverse some of the filtering that was done when the page was created
     * by the SdmCms() class. @see SdmCms::sdmCmsUpdateContent() for
     * more information on how data is filtered on page creation</p>
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
     * @param object $sdmassembler_dataObject <p>The Content object for the requested page.</p>
     */
    private function loadCoreApps($sdmassembler_dataObject) {
        // store parent (i.e. SdmCore) in an appropriatly named var to give apps easy access
        $sdmcore = new parent;
        // store object in an appropriatly named var to give apps easy access
        $sdmassembler = $this;
        // store requested page (determined by core) in an appropriatly named var to give apps easy access
        $sdmassembler_requestedpage = $this->determineRequestedPage();
        // store requested page (determined by core) in an appropriatly named var to give apps easy access
        $sdmassembler_dataObject = $sdmassembler_dataObject;
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

    /**
     * <p>Incorporates app output into the page.</p>
     * <p>This method is intended for use by Core and User apps. It provides
     * a simple method for incorporating an app's output into the page. It is
     * ok to call this method multiple times within an app.</p>
     * <p>If provided, the $options array is used to specify how the app's output
     * is to be incorporated.</p>
     * @param object $dataObject <p>The sites data object. (This is most likely the
     * $sdmassembler_dataObject var provided by the SDM_Assembler.<br />
     * Note: We need to typehint for security, however PHP has no common
     * object ancestor so we cant specify <i>object</i> because most likely
     * the type of this argument will be an instance of stdClass, which is
     * technically an object but will not neccessarily return as type object
     * when checked with typehinting.<br />
     * @see http://stackoverflow.com/questions/13287593/stdclass-and-type-hinting for more
     * info on why this happens.<br />@TODO: It may be best NOT to typehint the $dataObject
     * argument as it may introduce bugs if an actual object is passed to this method.</p>
     * @param string $output <p>A plain text or HTML string to be used as the apps output.</p>
     * @param array $options (optional) <p>Array of options that determine how an app's
     * output is incorporated. If not specified, then the app will be incorporated into
     * all pages and will be assigned to the 'main_content' wrapper that is part of, and,
     * required by SDM CORE.<br />
     * <h3><b>Overview of $options ARRAY:</b></h3>
     * <ul>
     *   <li>'wrapper' : The content wrapper the app is to be incorporated into.
     *                   If not specified then 'main_content' is assumed</li>
     *   <li>'incmethod' : Determines how app out put should be incorporated.<br />
     *                     Options for 'incmethod' are <b><i>append</i></b>, <b><i>prepend</i></b>, and <b><i>overwrite</i></b>.</li>
     *   <li>'incpages' : Array of pages to incorporate the app output into.</li>
     *   <li>'ignorepages' : Array of pages NOT to incoporate the app output into.</li>
     * </ul>
     * <b>NOTE: If a page is found in both the 'incpages' and 'ignorepages' arrays then
     *          the app output will be ignored on that page. This is for security, best to assume
     *          in such a case that the developer meant to ignore a page if the developer passes
     *          a page to both the 'incpages' and 'ignorepages' arrays.</b>
     * </p>
     * @param bool $devmode <p>If set to true, information about the $dataobject after it is processed by this method
     * will be displayed at the top of the page for the most recent call to this method. This is usefule when developing apps.</p>
     * @return object The modified data object.
     */
    public function incorporateAppOutput(stdClass $dataObject, $output, array $options = array(), $devmode = FALSE) {
        // determine which app this output came from
        $calledby = ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', str_replace(array('/', '.php'), '', strrchr(debug_backtrace()[0]['file'], '/')))); // trys to determine which app called this method using debug_backtrace() @see http://php.net/manual/en/function.debug-backtrace.php | basically were just filtering the name path of the file that this method was called to so it displays in a format that is easy to read, we know that the calling file will contain the app name since all apps must name their main php file according to this case insensitive naming convention : APPNAME.php
        // determine the requested page
        $requestedPage = $this->determineRequestedPage();
        /* OPTIONS ARRAY check| Review $options array values to insure they exist in prep checks that determine app how app should be incorporated | If they werent passed in via the $options argument then they will be assigned a default value */
        // if $options['wrapper'] is not set
        if (!isset($options['wrapper'])) {
            $options['wrapper'] = 'main_content';
        }
        // if ingorepages array was not passed to the $options array create it
        if (!isset($options['ignorepages'])) {
            $options['ignorepages'] = array();
        }
        // if incpages array was not passed to the $options array create it
        if (!isset($options['ignorepages'])) {
            $options['incpages'] = array();
        }
        /* DATAOBJECT check | Make sure the properties we are modifying exist to prevent throwing any PHP errors */
        // if no page exists for app in the core, then create a placeholder object for it to avoid PHP Errors, Notices, and Warnings
        if (!isset($dataObject->content->$requestedPage)) {
            $dataObject->content->$requestedPage = new stdClass();
        }
        // if target wrapper doesn't exist then create a placeholder for it to avoid any PHP Errors, Notices, or Warnings
        if (!isset($dataObject->content->$requestedPage->$options['wrapper'])) {
            $dataObject->content->$requestedPage->$options['wrapper'] = '';
        }
//        if ($devmode === TRUE) {
//            $this->sdm_read_array(array('STAGE' => '1', 'DEV ARRAY FOR' => 'incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State Before Incorporation of App Output' => $dataObject, 'debug_backtrace' => debug_backtrace()));
//        }
        switch (!empty($options)) {
            case TRUE:
                if (!in_array($requestedPage, $options['ignorepages'])) {
                    // if not in ignorepages array and incpages is empty assume any page not in ignore array can incorporate app output
                    if (empty($options['incpages'])) {
                        if ($options['incmethod'] === 'prepend') {
                            $dataObject->content->$requestedPage->$options['wrapper'] = $output . $dataObject->content->$requestedPage->$options['wrapper'];
                            if ($devmode === TRUE) {
                                $this->sdm_read_array(array('incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State After Incorporation of App Output' => $dataObject, '$options' => $options));
                            }
                        } else if ($options['incmethod'] === 'overwrite') {
                            $dataObject->content->$requestedPage->$options['wrapper'] = $output;
                            if ($devmode === TRUE) {
                                $this->sdm_read_array(array('incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State After Incorporation of App Output' => $dataObject, '$options' => $options));
                            }
                        } else { // default is to append
                            $dataObject->content->$requestedPage->$options['wrapper'] .= $output;
                            if ($devmode === TRUE) {
                                $this->sdm_read_array(array('incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State After Incorporation of App Output' => $dataObject, '$options' => $options));
                            }
                        }
                    }
                    // else if incpages array is not empty only incorporate app output into pages in the incpages array
                    else if (in_array($requestedPage, $options['incpages'])) {
                        if ($options['incmethod'] === 'prepend') {
                            $dataObject->content->$requestedPage->$options['wrapper'] = $output . $dataObject->content->$requestedPage->$options['wrapper'];
                            if ($devmode === TRUE) {
                                $this->sdm_read_array(array('incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State After Incorporation of App Output' => $dataObject, '$options' => $options));
                            }
                        } else if ($options['incmethod'] === 'overwrite') {
                            $dataObject->content->$requestedPage->$options['wrapper'] = $output;
                            if ($devmode === TRUE) {
                                $this->sdm_read_array(array('incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State After Incorporation of App Output' => $dataObject, '$options' => $options));
                            }
                        } else { // default is to append
                            $dataObject->content->$requestedPage->$options['wrapper'] .= $output;
                            if ($devmode === TRUE) {
                                $this->sdm_read_array(array('incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State After Incorporation of App Output' => $dataObject, '$options' => $options));
                            }
                        }
                    }
                } // do nothing if in requested page is in ignore pages
//                if ($devmode === TRUE) {
//                    $this->sdm_read_array(array('STAGE' => '2', 'DEV ARRAY FOR' => 'incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State After Incorporation of App Output' => $dataObject, 'debug_backtrace' => debug_backtrace()));
//                }
                break;

            default: // default is to append the $output.
                $dataObject->content->$requestedPage->$options['wrapper'] .= $output;
                if ($devmode === TRUE) {
                    $this->sdm_read_array(array('incorporateAppOutput()', 'method called by app' => $calledby, 'App Output' => $output, 'Data Object State After Incorporation of App Output' => $dataObject, '$options' => $options));
                }
                break;
        }
        return $dataObject;
    }

}

