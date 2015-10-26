<?php

/**
 * The <b>SdmAssembler</b> is responsible for loading and assembleing page content.
 * It is also responsible for loading and assemlbing CORE and user apps.
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
    public static function sdmAssemblerInitializeAssembler() {
        if (!isset(self::$Initialized)) {
            self::$Initialized = new SdmAssembler;
        }
        return self::$Initialized;
    }

    /**
     * <p>Returns the HTML header for the page as a string. The SdmAssembler will give apps
     * a chance to modify the header prior to returning it. This method also reads app and theme
     * .as files if provided and incorporates stylesheets scripts and meta
     * tags defined in those files into the html header.</p>
     * <p>NOTE: At the moment apps are only allowed to define the scripts property in their .as files.
     * This will change in the future, however apps may not ever be allowed to provide stylesheets
     * in order to keep styling and extended functionality separate, however this is debatable
     * because not allowing apps to serve stylesheets defined in their .as files stylesheets
     * property will force app developers to write clunky code into their apps in order
     * to add app specific styles to their apps output without modifying the current theme.</p>
     * <p>Also note, app scripts will always be loaded first so that they take precedent over theme scripts.
     * This is done to encourage developers to build apps to serve their scripts rather then serving them
     * from a specific theme. Scripts served from a theme cannot be turned off from the UI and they will only
     * work if the theme serving them is set to be the current theme, however scripts
     * severed from apps can be turned off since apps can be turned off. The only reason themes are allowed
     * to serve scripts from their .as files is because there may be rare circumstances where a developer
     * needs a script for a specific theme.</p>
     * @return string <p>The HTML header for the page.</p>
     */
    public function sdmAssemblerAssembleHtmlHeader() {
        /**
         * Get enabled app scripts.
         */
        // init $appScriptProps var
        $appScriptProps = '';
        foreach ($this->sdmCoreDetermineEnabledApps() as $app) {
            // get userApp .as properties
            $appScriptProps .= ($this->sdmAssemblerAssembleHeaderProperties('scripts', 'userApp', $app) === FALSE ? '' : $this->sdmAssemblerAssembleHeaderProperties('scripts', 'userApp', $app));
            // get coreApp .as properties
            $appScriptProps .= ($this->sdmAssemblerAssembleHeaderProperties('scripts', 'coreApp', $app) === FALSE ? '' : $this->sdmAssemblerAssembleHeaderProperties('scripts', 'coreApp', $app));
        }
        /** At the moment only app scipts are incorporated, stylesheets and meta tags are not yet supported for apps */
        return '
            <!DOCTYPE html>
            <html>
                <head>
                    <title>' . (isset($_GET['page']) ? ucfirst($_GET['page']) : ucfirst('homepage')) . '</title>
                    ' . ($appScriptProps !== FALSE ? $appScriptProps : '') . '
                    ' . $this->sdmAssemblerAssembleHeaderProperties('meta') . '
                    ' . $this->sdmAssemblerAssembleHeaderProperties('stylesheets') . '
                    ' . $this->sdmAssemblerAssembleHeaderProperties('scripts') . '
                    <!-- set base url -->
                    <base href="' . $this->sdmCoreGetRootDirectoryUrl() . '" target="_self">
                </head>
            <body class="' . $this->sdmCoreDetermineCurrentTheme() . '">
            ';
    }

    /**
     * <p>Assembles html for header properties specified in a theme or app's .as file
     * and assembles the html necessary to incorporate them into the page.</p>
     * @param string $targetProperty <p>Header property to assemble.</p>
     * @param string $source <p>Determines where the .as file should be loaded from. Either
     *                       <b>theme</b>, <b>userApp</b>, or <b>coreApp</b>.</p>
     *                       <p><i>NOTE: If source is not set then it will be assumed that the $property
     *                          values should be read from the current themes .as file<br/>
     *                          <b>IMPORTANT: If $source is set then $sourceName must also be set.</b></i></p>
     * @param string $sourceName <p>The name of the theme or app whose .as file we are reading .as property values from.</p>
     * @return string <p>The html for the header property.</p>
     */
    private function sdmAssemblerAssembleHeaderProperties($targetProperty, $source = NULL, $sourceName = NULL) {
        // initialize $html var
        $html = '<!-- ' . ($source === NULL ? $this->sdmCoreDetermineCurrentTheme() . ' Theme ' . $targetProperty : ($source === 'userApp' ? 'User App' : ($source === 'coreApp' ? 'Core App' : 'Theme')) . ' ' . $sourceName . ' ' . $targetProperty) . ' -->';
        // store initial $html value so we can perform a check later to see if anything was appended to $html, if nothing was appended to $html by the end of this method then the attempt to load the .as file properties failed
        $initHtml = $html;
        // determine directory to load resources set by properties such as stylesheets, or scripts
        $path = ($source === NULL ? $this->sdmCoreGetCurrentThemeDirectoryUrl() : ($source === 'theme' ? $this->sdmCoreGetThemesDirectoryUrl() . '/' . $sourceName : ($source === 'userApp' ? $this->sdmCoreGetUserAppDirectoryUrl() . '/' . $sourceName : ($source === 'coreApp' ? $this->sdmCoreGetUserAppDirectoryUrl() . '/' . $sourceName : NULL))));
        //$this->sdmCoreSdmReadArray(array('path' => $path));
        $properties = ($source === NULL ? $this->sdmAssemblerGetAsProperty($targetProperty) : $this->sdmAssemblerGetAsProperty($targetProperty, $source, $sourceName));
        if ($properties !== FALSE) {
            // assemble property html
            if (!empty($properties) === TRUE) {
                foreach ($properties as $property) {
                    if ($property == '') {
                        error_log('.as file property "' . $targetProperty . '" has no value. | Source:  ' . $sourceName . '');
                    } else {
                        switch ($targetProperty) {
                            case 'stylesheets':
                                $html .= '<link rel="stylesheet" type="text/css" href="' . $path . '/' . trim($property) . '.css">';
                                break;
                            case 'scripts':
                                $html .= '<script src="' . $path . '/' . trim($property) . '.js"></script>';
                                break;
                            case 'meta':
                                // At the moment meta tags are being hardcoed until it is determined how to parse the values in a .as file and translate them into the more complex structure of a meta tag.
                                $html .= '<meta name="description" content="Website powered by the SDM CMS"><meta name="author" content="Sevi Donnelly Foreman"><meta http-equiv="refresh" content="3000">';
                                break;
                            default:
                                error_log('Value "' . $property . '" from .as file property "' . $targetProperty . '" was not loaded, custom .as properties are not recognized. | Source:  ' . $sourceName);
                                break;
                        }
                    }
                }
            } else {
                error_log('.as file property "' . $targetProperty . '" was not loaded because the "' . $targetProperty . '" property does not exist in the .as file. | Source:  ' . $sourceName);
            }
        } else {
            // commented out so error log does not get clutterd by these warnings
            //error_log('.as file property "' . $targetProperty . '" was not loaded because a .as file is not provided by source : ' . $sourceName);
        }
        return ($html === $initHtml ? FALSE : $html);
    }

    /**
     * <p>Returns an array of values for a specified property from a
     * specified theme or app <b>.as</b> file.</p>
     * @param string $property <p>The property whose values should be returned in an array. (e.g., 'stylesheets')</p>
     * @param string $source <p>Determines where the .as file should be loaded from. Either
     *                       <b>theme</b>, <b>userApp</b>, or <b>coreApp</b>.</p>
     *                       <p><i>NOTE: If source is not set then it will be assumed that the $property
     *                          values should be read from the current themes .as file as this was the original
     *                          purpose of this method. It evolved to target specific .as files from specific
     *                          apps and thems so developers could have their themes and apps incorporate stylesheets,
     *                          scripts, and add meta tags to the header of the page by provideing a .as file.
     *                          This method can also be used by developers in apps and thems to do things
     *                          with the values set in a specific .as file. For instance, maybe an app
     *                          provides a UI display to show the current .as settings of a specific theme or app.
     *                          Such a feat would be accomlished by calling this method and then doing something
     *                          with the returned array.<br/>
     *                          <b>IMPORTANT: If $source is set then $sourceName must also be set.</b></i></p>
     * @param string $sourceName <p>The name of the theme or app whose .as file we are reading .as property values from.</p>
     * @return array <p>An array of values for the specified $property.
     *               <br/><i><b>Note:</b> An empty array will be returned if
     *               any of the following are TRUE:</i></p>
     *               <ul>
     *                  <li>if property is not set in .as file</li>
     *                  <li>if it is set but it does not have any values</li>
     *                  <li>if the method failed to get the property values.</li>
     *               </ul>
     */
    private function sdmAssemblerGetAsProperty($property, $source = NULL, $sourceName = NULL) {
        switch ($source) {
            case 'theme':
                // read .as file into an array
                $asFile = @file($this->sdmCoreGetThemesDirectoryPath() . '/' . $sourceName . '/' . $sourceName . '.as');
                break;
            case 'userApp':
                // read .as file into an array
                $asFile = @file($this->sdmCoreGetUserAppDirectoryPath() . '/' . $sourceName . '/' . $sourceName . '.as');
                break;
            case 'coreApp':
                // read .as file into an array
                $asFile = @file($this->sdmCoreGetCoreAppDirectoryPath() . '/' . $sourceName . '/' . $sourceName . '.as');
                break;
            default: // defaults to reading the current theme's .as file
                $asFile = @file($this->sdmCoreGetCurrentThemeDirectoryPath() . '/' . $this->sdmCoreDetermineCurrentTheme() . '.as');
                break;
        }
        if ($asFile !== FALSE) {
            // loop through array | i.e., loop through each line of the .as file
            foreach ($asFile as $line) {
                // check if current $line is for $property
                if (strstr($line, '=', TRUE) === $property) {
                    // store property values in an array
                    $properties = explode(',', $this->sdmCoreStrSlice($line, '=', ';'));
                }
            }
        }
        return ($asFile === FALSE ? FALSE : (isset($properties) === TRUE ? $properties : array()));
    }

    /**
     * <p>Loads and assembles a content object for the requested page.</p>
     * @return object A content object for the requested page.
     */
    public function sdmAssemblerLoadAndAssembleContentObject() {
        $page = $this->sdmCoreDetermineRequestedPage();
        // load our data object
        $sdmassembler_dataObject = $this->sdmCoreLoadDataObject();
        // load and assemble apps
        $this->sdmAssemblerLoadCoreApps($sdmassembler_dataObject);
        // make sure content exists, if it does return it, if not, print a content not found message
        switch (isset($sdmassembler_dataObject->content->$page)) {
            case TRUE:
                //var_dump($sdmassembler_dataObject->content->$page);
                $sdmassembler_dataObject = $this->sdmAssemblerPreparePageForDisplay($sdmassembler_dataObject->content->$page);
                return $sdmassembler_dataObject;
                break;
            default:
                // log bad request to our badRequestsLog.log file
                $badRequestId = chr(rand(65, 90)) . rand(10, 99) . chr(rand(65, 90)) . rand(10, 99);
                $badRequestDate = date('d-M-Y H:i:s e');
                $badRequestUrl = $this->sdmCoreGetRootDirectoryUrl() . '/index.php?' . $_SERVER['QUERY_STRING'];
                $truncatedBadRequsetUrl = (strlen($badRequestUrl) > 112 ? substr($badRequestUrl, 0, 112) . '...' : $badRequestUrl);
                $linkedByInfo = (isset($_GET['linkedByMenu']) === TRUE ? 'Request Origin: Internal' . PHP_EOL . '- Menu:' . $_GET['linkedByMenu'] . PHP_EOL . (isset($_GET['linkedByMenuItem']) ? '- Menu Item: ' . $_GET['linkedByMenuItem'] : 'menu item unknown') : (isset($_GET['linkedBy']) === TRUE ? 'Request Origin: ' . $_GET['linkedBy'] : 'Request Origin: Unknown'));
                $errorMessage = '----- BAD REQUEST [' . $badRequestDate . '] -----' . PHP_EOL .
                        'Bad request id: ' . $badRequestId . PHP_EOL .
                        'Requested Page: ' . $page . PHP_EOL .
                        'Requested Url: ' . $badRequestUrl . PHP_EOL .
                        'Request Made by User: ' . 'anonymous' . PHP_EOL .
                        $linkedByInfo . PHP_EOL .
                        '---------------------------------------------------------------' . PHP_EOL;
                error_log($errorMessage, 3, $this->sdmCoreGetCoreDirectoryPath() . '/logs/badRequestsLog.log');
                return json_decode(json_encode(array('main_content' => '<p>The requested page at <b>' . $this->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $page . '</b> could not be found. Check the url to for typos. If error persists and your sure this content should exist contact the site admin  at (@TODO:DYNAMICALLY PLACE ADMIN EMAIL HERE) to report the error.</p><p>' . 'Bad request id: ' . $badRequestId . '</p><p>' . 'Requested Page: ' . $page . '</p><p>Requested Url <i>(trimmed for display)</i>: ' . $truncatedBadRequsetUrl . '</p>')));
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
    private function sdmAssemblerPreparePageForDisplay($page) {
        foreach ($page as $name => $value) {
            $page->$name = html_entity_decode($value, ENT_HTML5, 'UTF-8');
        }
        return $page;
    }

    /**
     * Loads enabled CORE apps.
     * @todo Change name to loadApps, more accurate description b/c this method is responsible for
     * loading all apps
     * @param object $sdmassembler_dataObject <p>The Content object for the requested page.</p>
     */
    private function sdmAssemblerLoadCoreApps($sdmassembler_dataObject) {
        // store parent (i.e. SdmCore) in an appropriatly named var to give apps easy access
        $sdmcore = new parent;
        // store object in an appropriatly named var to give apps easy access
        $sdmassembler = $this;
        // @TODO : Unless you find good reason to keep it, the $sdmassembler_requestedpage var should be depreceated because SDM CORE provides a method for determining the requested page... store requested page (determined by CORE) in an appropriatly named var to give apps easy access
        $sdmassembler_requestedpage = $this->sdmCoreDetermineRequestedPage();
        // store data object in an appropriatly named for to give apps easy access
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
            /**
             * Check if the app has a .gk file, if it does then get it's parameters.
             * NOTE: If the app does NOT have a .gk file then
             * SdmGatekeeper::sdmGatekeeperReadAppGkParams() will return
             * FALSE.
             */
            $gkParams = SdmGatekeeper::sdmGatekeeperReadAppGkParams($app);
            //$this->sdmCoreSdmReadArray(array('app' => $app, 'GkParams' => ($gkParams === FALSE ? 'FALSE' : $gkParams)));
            /**
             * If .gk file does NOT exist, SdmGatekeeper::sdmGatekeeperReadAppGkParams()
             * returned FALSE, then assume app is not restricted to role,
             * if .gk file exists then check the roles parameter to see which roles
             * have permission to use this app, if the roles parameter has the 'all' value
             * in it then all users will be able to use this app.
             */
            $userClear = ($gkParams === FALSE || in_array(SdmGatekeeper::SdmGatekeeperDetermineUserRole(), $gkParams['roles']) || in_array('all', $gkParams['roles']) ? TRUE : FALSE);
            if ($userClear === TRUE) {
                if (property_exists($settings->enabledapps, $app)) {
                    // load apps
                    if (file_exists($this->sdmCoreGetCoreAppDirectoryPath() . '/' . $app . '/' . $app . '.php')) {
                        require_once($this->sdmCoreGetCoreAppDirectoryPath() . '/' . $app . '/' . $app . '.php');
                    } else if (file_exists($this->sdmCoreGetUserAppDirectoryPath() . '/' . $app . '/' . $app . '.php')) {
                        require($this->sdmCoreGetUserAppDirectoryPath() . '/' . $app . '/' . $app . '.php');
                    } else {
                        echo '<!-- site has no enabled apps -->';
                    }
                }
            } else { // user does not have permission to use this app
                $this->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, 'You do not have permission to be here.', array('incpages' => array($app)));
            }
        }
    }

    /**
     * Returns <p>The required closing HTML tags for the page.</p>
     * @return <p>string The required HTML closing tags as a string.</p>
     */
    public function sdmAssemblerAssembleHtmlRequiredClosingTags() {
        return '
    <!--This site was built using the SDM CMS content management system which was designed and developed by Sevi Donnelly Foreman in the year 2014.-->
    <!--To contact the developer of the SDM CMS write to sdmwebsdm@gmail.com.-->
    <!--Note: Sevi is not necessarily the author of this site, he is just the developer of Content Management System that is used to build/maintain this site.-->
    </body>
    </html>
    ';
    }

    /**
     * <p style="font-size:9px;">Incorporates app output into the page.</p>
     * <p style="font-size:9px;">This method is intended for use by CORE and User apps. It provides
     * a simple method for incorporating an app's output into the page. It is
     * ok to call this method multiple times within an app.</p>
     * <p style="font-size:9px;">If provided, the $options array is used to specify how the app's output
     * is to be incorporated.</p>
     * <p style="font-size:9px;"><b>NOTE</b>: If the requested page (determined internally) does not exist
     * in CORE, as an enabled app, or in the $options array's 'incpages' array then the dataObject will not be modifed
     * and the apps output will not be incorporated. This is for security, and prevents requests to
     * non-existent pages from succsesfully taking user to a dynamicaly generated page.
     * This method, in order to allow apps to function without creating a page for their output in CORE,
     * creates a place holder page in the datObject for when the requested page does not exist in CORE.
     * So, if the requested page does not exist in CORE, it must at least exist as a on of the
     * pages specified in the $options array's 'incpages' array. Without this check we could pass anything
     * to the page argument in the url and the SDM CMS would gnereate a page for it.
     * <br /><br />i.e, http://example.com/index.php?page=NonExistentPage would work if we did not check for it in CORE
     * and in the 'incpages' array</p>
     * @param object $dataObject <p style="font-size:9px;">The sites data object. (This is most likely the
     * $sdmassembler_dataObject var provided by the SDM_Assembler)<br />
     * Note: We need to typehint for security, however PHP has no common
     * object ancestor so we cant specify <i>object</i> because most likely
     * the type of this argument will be an instance of stdClass, which is
     * technically an object but will not neccessarily return as type object
     * when checked with typehinting.<b>*</b><br />
     * @see <b>*</b>http://stackoverflow.com/questions/13287593/stdclass-and-type-hinting for more
     * info on why this happens.<br />@TODO: It may be best NOT to typehint the $dataObject
     * argument as it may introduce bugs if an actual object is passed to this method.</p>
     * @param string $output <p style="font-size:9px;"p>A plain text or HTML string to be used as the apps output.</p>
     * @param array $options (optional) <p style="font-size:9px;">Array of options that determine how an app's
     * output is incorporated. If not specified, then the app will be incorporated into
     * all pages and will be assigned to the 'main_content' wrapper that is part of, and,
     * required by SDM CORE.<br />
     * <br /><b>Overview of $options ARRAY:</b>
     * <ul style="font-size:9px;">
     *   <li>'wrapper' : The content wrapper the app is to be incorporated into.
     *                   If not specified then 'main_content' is assumed</li>
     *   <li>'incmethod' : Determines how app output should be incorporated.<br />
     *                     Options for 'incmethod' are <b><i>append</i></b>,
     *                     <b><i>prepend</i></b>, and <b><i>overwrite</i></b>. Defaults to <b>append</b>.
     *   </li>
     *   <li>'incpages' : Array of pages to incorporate the app output into. You can also pass in the name
     *                    of an app and then any page that app generates will also incorporate the app out put
     *                    as long as the page the app generates shares the same name as the app itself.
     *                    (i.e. if you incpages has an item ExampleApp then the page ExampleApp
     *                          will incorporate app output even if the page ExampleApp does not
     *                          exist in CORE.)
     *  <br />Note: If incpages is not set then it is assumed that all pages
     *              are to incorporate app output. If an empty array is passed
     *              then NO pages will incorporate app output.
     *              (i.e., passing an empty array is basically the same as passing
     *                     in an ignorepages array that contains the names of all pages
     *                     and enabled apps.</li>
     *   <li>'ignorepages' : Array of pages NOT to incoporate the app output into. This
     *                       array can include the names of Apps that should NOT incorporate
     *                       this output into pages that they generate.
     *                      <br />(i.e, if an ExampleApp esixts in ignorePages then any
     *                             page generated by the ExampleApp app will not incroporate
     *                             the appoutput)
     *   </li>
     *   <li>'roles' : Array of roles that can view this output.</li>
     * </ul>
     * <b>NOTE: If a page is found in both the 'incpages' and 'ignorepages' arrays then
     *          the app output will be ignored on that page. This is for security, best to assume
     *          in such a case that the developer meant to ignore a page if the developer passes
     *          a page to both the 'incpages' and 'ignorepages' arrays.</b>
     * </p>
     * @param bool $devmode <p style="font-size:9px;">If set to true, information about the $dataobject after it is processed by this method
     * will be displayed at the top of the page for the most every call to this method for the requested page. This is useful when developing apps.</p>
     * @return object <p style="font-size:9px;">The modified data object.</p>
     */
    public function sdmAssemblerIncorporateAppOutput(stdClass $dataObject, $output, array $options = array(), $devmode = FALSE) {
        // determine which app this output came from
        $calledby = ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', str_replace(array('/', '.php'), '', strrchr(debug_backtrace()[0]['file'], '/')))); // trys to determine which app called this method using debug_backtrace() @see http://php.net/manual/en/function.debug-backtrace.php | basically were just filtering the name path of the file that this method was called to so it displays in a format that is easy to read, we know that the calling file will contain the app name since all apps must name their main php file according to this case insensitive naming convention : APPNAME.php
        // determine the requested page
        $requestedPage = $this->sdmCoreDetermineRequestedPage();
        /* OPTIONS ARRAY check| Review $options array values to insure they exist in prep for checks that determine how app should be incorporated | If they werent passed in via the $options argument then they will be assigned a default value and stored in the $options array */
        // if $options['wrapper'] is not set
        if (!isset($options['wrapper'])) {
            $options['wrapper'] = 'main_content';
        }
        // if incmethod was not passed to the $options array create it
        if (!isset($options['incmethod'])) {
            $options['incmethod'] = 'append';
        }
        // if ingorepages array was not passed to the $options array create it
        if (!isset($options['ignorepages'])) {
            $options['ignorepages'] = array();
        }
        // if incpages array was not passed to the $options array create it
        if (!isset($options['incpages'])) {
            /* For security, we check to see if the incpages array was passed to the options array.
             * If it was then leave it alone and use it as it is, if it wasn't then we assume the
             * developer meant to incorporate into all pages so we create an incpages array that
             * contains all the pages in CORE as well as any enabled apps so any app generated pages
             * will also incroporate app output.
             * Also note that if inpages is empty then it will be assumed the developer
             * does NOT want to incorporate app output into any page.
             * i.e.,
             *   sdmAssemblerIncorporateAppOutput($dataObject, $output, array('incpages' => array());// app out put will NOT be incorporated into any pages because incpages is empty
             *   sdmAssemblerIncorporateAppOutput($dataObject, $output);// app out put will be incorporated into all pages because incpages does not exist, and will therefore be created and configured with pre-determined internal default values
             */
            $pages = $this->sdmCoreDetermineAvailablePages();
            $enabledApps = json_decode(json_encode($this->sdmCoreDetermineEnabledApps()), TRUE);
            $options['incpages'] = array_merge($pages, $enabledApps);
        }
        // if $options['roles'] is not set then we assume all users should see this app output and we add the special 'all' value to the $otpions['roles'] array, if $options['roles'] is empty we assume no users can see this app output.
        if (!isset($options['roles'])) {
            $options['roles'] = array('all');
        }
        // first we check if app output is restricted to certain roles. if it is we check that current user role matches one of the valid roles for the app. If the special 'all' role is in the $options['roles'] array then all users see the app output
        $validUser = (in_array(SdmGatekeeper::SdmGatekeeperDetermineUserRole(), $options['roles']) || in_array('all', $options['roles']) ? TRUE : FALSE);
        if ($validUser === TRUE) {
            // Check that $requested page exists in CORE or or is passed in as an option via the options array's incpages array
            if (in_array($requestedPage, $this->sdmCoreDetermineAvailablePages()) === TRUE || in_array($requestedPage, $options['incpages']) === TRUE) {
                /* DATAOBJECT check | Make sure the properties we are modifying exist to prevent throwing any PHP errors */
                // if no page exists for app in the CORE, then create a placeholder object for it to avoid PHP Errors, Notices, and Warnings
                if (!isset($dataObject->content->$requestedPage)) {
                    $dataObject->content->$requestedPage = new stdClass();
                }
                // if target wrapper doesn't exist then create a placeholder for it to avoid any PHP Errors, Notices, or Warnings
                if (!isset($dataObject->content->$requestedPage->$options['wrapper'])) {
                    $dataObject->content->$requestedPage->$options['wrapper'] = '';
                }

                // make sure requested page is not in the ignorepages array
                if (!in_array($requestedPage, $options['ignorepages'])) {
                    // PRE PROCESSING DEV MODE OUTPUT
                    if ($devmode === TRUE) {
                        $this->sdmCoreSdmReadArray(array('Call To Method' => 'sdmAssemblerIncorporateAppOutput()', 'Method called by app' => $calledby, 'STAGE' => 'PRE_PROCESSING', 'OPTIONS' => $options, 'App Output' => $output, 'Data Object State' => $dataObject,));
                    }
                    // if not in ignorepages array and incpages is empty assume any page not in ignore array can incorporate app output
                    // Only incorporate app output if requested page matches one of the items in incpages
                    if (in_array($requestedPage, $options['incpages'], TRUE)) {
                        if ($options['incmethod'] === 'prepend') {
                            $dataObject->content->$requestedPage->$options['wrapper'] = $output . $dataObject->content->$requestedPage->$options['wrapper'];
                            // PREPEND DEV MODE OUTPUT
                            if ($devmode === TRUE) {
                                $this->sdmCoreSdmReadArray(array('Call To Method' => 'sdmAssemblerIncorporateAppOutput()', 'Method called by app' => $calledby, 'STAGE' => 'PREPENDING', 'OPTIONS' => $options, 'App Output' => $output, 'Data Object State' => $dataObject,));
                            }
                        } else if ($options['incmethod'] === 'overwrite') {
                            $dataObject->content->$requestedPage->$options['wrapper'] = $output;
                            // OVERWRITE DEV MODE OUTPUT
                            if ($devmode === TRUE) {
                                $this->sdmCoreSdmReadArray(array('Call To Method' => 'sdmAssemblerIncorporateAppOutput()', 'Method called by app' => $calledby, 'STAGE' => 'OVERWRITEING', 'OPTIONS' => $options, 'App Output' => $output, 'Data Object State' => $dataObject,));
                            }
                        } else { // default is to append
                            $dataObject->content->$requestedPage->$options['wrapper'] .= $output;
                            // APPEND (default) DEV MODE OUTPUT
                            if ($devmode === TRUE) {
                                $this->sdmCoreSdmReadArray(array('Call To Method' => 'sdmAssemblerIncorporateAppOutput()', 'Method called by app' => $calledby, 'STAGE' => 'APPENDING', 'OPTIONS' => $options, 'App Output' => $output, 'Data Object State' => $dataObject,));
                            }
                        }
                    }
                } // do nothing if in requested page is in ignore pages
            } // end check if requested page exists in CORE or as an enabled app
        } // end check roles
        return $dataObject;
    }

    /**
     * <p>Assembles the html content for a given $wrapper and returns it as a string. This method
     * is meant to be called from within a themes page.php file.</p>
     * @param string $wrapper <p>The wrapper to assemble html</p>
     * @param stdClass $dataObject <p>The $sdmassembler_themeContentObject variable that is created
     * by startup.php's call to SdmAssembler::sdmAssemblerLoadAndAssembleContentObject() during the
     * startup process. The $sdmassembler_themeContentObject is always avaialbe to all themes.
     * <br>See: <i>/core/config/startup.php</i> for more info</p>
     * @return type
     */
    public static function sdmAssemblerGetContentHtml($wrapper, stdClass $sdmassembler_themeContentObject) {
        // initialize the SdmNms so we can add our menus to the page.
        $nms = new SdmNms();
        $wrapperAssembledContent = (isset($sdmassembler_themeContentObject->$wrapper) ? $sdmassembler_themeContentObject->$wrapper : '<!-- ' . $wrapper . ' placeholder -->');
        $content = $nms->sdmNmsGetWrapperMenusHtml($wrapper, $wrapperAssembledContent);
        return $content;
    }

}

