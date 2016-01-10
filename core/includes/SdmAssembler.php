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
class SdmAssembler extends SdmNms
{

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
    public function sdmAssemblerAssembleHtmlHeader()
    {
        /**
         * Get enabled app scripts.
         */
        // init $appScriptProps var
        $appScriptProps = '';
        foreach ($this->sdmCoreDetermineEnabledApps() as $app) {
            // get userApp .as properties
            $appScriptProps .= ($this->sdmAssemblerAssembleHeaderProperties('scripts', 'userApp', $app) === false ? '' : $this->sdmAssemblerAssembleHeaderProperties('scripts', 'userApp', $app));
            // get coreApp .as properties
            $appScriptProps .= ($this->sdmAssemblerAssembleHeaderProperties('scripts', 'coreApp', $app) === false ? '' : $this->sdmAssemblerAssembleHeaderProperties('scripts', 'coreApp', $app));
        }
        /** At the moment only app scipts are incorporated, stylesheets and meta tags are not yet supported for apps */
        return '
            <!DOCTYPE html>
            <html>
                <head>
                    <title>' . (isset($_GET['page']) ? ucfirst($_GET['page']) : ucfirst('homepage')) . '</title>
                    ' . ($appScriptProps !== false ? $appScriptProps : '') . '
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
    private function sdmAssemblerAssembleHeaderProperties($targetProperty, $source = null, $sourceName = null)
    {
        // initialize $html var
        $html = '<!-- ' . ($source === null ? $this->sdmCoreDetermineCurrentTheme() . ' Theme ' . $targetProperty : ($source === 'userApp' ? 'User App' : ($source === 'coreApp' ? 'Core App' : 'Theme')) . ' ' . $sourceName . ' ' . $targetProperty) . ' -->';
        // store initial $html value so we can perform a check later to see if anything was appended to $html, if nothing was appended to $html by the end of this method then the attempt to load the .as file properties failed
        $initHtml = $html;
        // determine directory to load resources set by properties such as stylesheets, or scripts
        $path = ($source === null ? $this->sdmCoreGetCurrentThemeDirectoryUrl() : ($source === 'theme' ? $this->sdmCoreGetThemesDirectoryUrl() . '/' . $sourceName : ($source === 'userApp' ? $this->sdmCoreGetUserAppDirectoryUrl() . '/' . $sourceName : ($source === 'coreApp' ? $this->sdmCoreGetCoreAppDirectoryUrl() . '/' . $sourceName : null))));
        //$this->sdmCoreSdmReadArray(array('path' => $path));
        $properties = ($source === null ? $this->sdmAssemblerGetAsProperty($targetProperty) : $this->sdmAssemblerGetAsProperty($targetProperty, $source, $sourceName));
        if ($properties !== false) {
            // assemble property html
            if (!empty($properties) === true) {
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
                                $html .= '<meta name="description" content="Website powered by the SDM CMS"><meta name="author" content="Sevi Donnelly Foreman"><meta http-equiv="refresh" content="3000"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
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
        return ($html === $initHtml ? false : $html);
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
     *               any of the following are true:</i></p>
     *               <ul>
     *                  <li>if property is not set in .as file</li>
     *                  <li>if it is set but it does not have any values</li>
     *                  <li>if the method failed to get the property values.</li>
     *               </ul>
     */
    private function sdmAssemblerGetAsProperty($property, $source = null, $sourceName = null)
    {
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
        if ($asFile !== false) {
            // loop through array | i.e., loop through each line of the .as file
            foreach ($asFile as $line) {
                // check if current $line is for $property
                if (strstr($line, '=', true) === $property) {
                    // store property values in an array
                    $properties = explode(',', $this->sdmCoreStrSlice($line, '=', ';'));
                }
            }
        }
        return ($asFile === false ? false : (isset($properties) === true ? $properties : array()));
    }

    /**
     * <p>Loads and assembles a content object for the requested page.</p>
     * @return object A content object for the requested page.
     */
    public function sdmAssemblerLoadAndAssembleContentObject()
    {
        // determine requested page
        $page = $this->sdmCoreDetermineRequestedPage();
        // load and assemble apps
        $this->sdmAssemblerLoadApps();
        /** make sure content exists, if it does return it, if not, return a content not found message and log the bad request to the bad requests log */
        // cast $sdmAssemblerDataObject->content->$page to an array so we can test if it is empty or not, works better then isset because the $sdmAssemblerDataObject->content->$page object may exist with no properties.
        $pageContent = (array)$this->DataObject->content->$page;
        switch (empty($pageContent)) {
            case false:
                $this->DataObject->content->$page = $this->sdmAssemblerPreparePageForDisplay($this->DataObject->content->$page);
                return true;
            default:
                // log bad request to our badRequestsLog.log file
                $badRequestId = chr(rand(65, 90)) . rand(10, 99) . chr(rand(65, 90)) . rand(10, 99);
                $badRequestDate = date('d-M-Y H:i:s e');
                $badRequestUrl = $this->sdmCoreGetRootDirectoryUrl() . '/index.php?' . $_SERVER['QUERY_STRING'];
                $truncatedBadRequsetUrl = (strlen($badRequestUrl) > 112 ? substr($badRequestUrl, 0, 112) . '...' : $badRequestUrl);
                $linkedByInfo = (isset($_GET['linkedByMenu']) === true ? 'Request Origin: Internal' . PHP_EOL . '- Menu:' . $_GET['linkedByMenu'] . PHP_EOL . (isset($_GET['linkedByMenuItem']) ? '- Menu Item: ' . $_GET['linkedByMenuItem'] : 'menu item unknown') : (isset($_GET['linkedBy']) === true ? 'Request Origin: ' . $_GET['linkedBy'] : 'Request Origin: Unknown'));
                $errorMessage = '----- BAD REQUEST [' . $badRequestDate . '] -----' . PHP_EOL .
                    'Bad request id: ' . $badRequestId . PHP_EOL .
                    'Requested Page: ' . $page . PHP_EOL .
                    'Requested Url: ' . $badRequestUrl . PHP_EOL .
                    'Request Made by User: ' . 'anonymous' . PHP_EOL .
                    $linkedByInfo . PHP_EOL .
                    '---------------------------------------------------------------' . PHP_EOL;
                error_log($errorMessage, 3, $this->sdmCoreGetCoreDirectoryPath() . '/logs/badRequestsLog.log');
                $this->DataObject->content->$page = json_decode(json_encode(array('main_content' => '<p>The requested page at <b>' . $this->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $page . '</b> could not be found. Check the url to for typos. If error persists and your sure this content should exist contact the site admin  at (@TODO:DYNAMICALLY PLACE ADMIN EMAIL HERE) to report the error.</p><p>' . 'Bad request id: ' . $badRequestId . '</p><p>' . 'Requested Page: ' . $page . '</p><p>Requested Url <i>(trimmed for display)</i>: ' . $truncatedBadRequsetUrl . '</p>')));
                return false;
        }
    }

    /**
     * Loads enabled apps.
     * @param object $sdmAssemblerDataObject <p>The Content object for the requested page.</p>
     * @return bool <p>false if any apps failed to load, true if no problems occured when loading
     * all apps.</p><p><b>NOTE</b>:<i>This method will return true even if this methods call to
     * $this->sdmAssemblerLoadApp() fails to load an app as a result of user having insufficient
     * privlages. Only actual failures will result in this method returning false.</i></p>
     */
    private function sdmAssemblerLoadApps()
    {
        $enabledApps = $this->sdmCoreDetermineEnabledApps();
        foreach ($enabledApps as $app) {
            $status[] = $this->sdmAssemblerLoadApp($app);
        }
        return (in_array(false, $status, true));
    }

    /**
     * Loads an individual app. This method should only be used internally by
     * the sdmAssembler()'s sdmAssemblerLoadApps() method.
     * @param string $app <p>The name of the app to load</p>
     * @return mixed <p>true if app was loaded, false if app could not be loaded as a result
     * of an error, such as the app not being found, or the string 'accessDenied' if app was
     * not loaded as a result of user not having sufficient privlages to use app.</p>
     */
    private function sdmAssemblerLoadApp($app)
    {
        // we make a copy of $this so apps will be able to utilize $this in their code through the $sdmassembler var. Basically, since we can't use $this from within our apps code, and even if we could it would not be wise as it would not be clear what $this was, $sdmassembler functions as a named alias so apps can do things like call $sdmassembler->SdmCoreSdmReadArray() from within their code.
        $sdmassembler = $this;
        // read app gatekeeper parameters
        $gkParams = $sdmassembler->sdmGatekeeperReadAppGkParams($app);
        /**
         * If $this->sdmGatekeeperReadAppGkParams()
         * returned false, then assume app is not restricted to role,
         * if .gk file exists then check the roles parameter to see which roles
         * have permission to use this app, if the roles parameter has the 'all' value
         * in it then all users will be able to use this app.
         */
        $userClear = ($gkParams === false || in_array($sdmassembler->SdmGatekeeperDetermineUserRole(), $gkParams['roles']) || in_array('all', $gkParams['roles']) ? true : false);
        $appPath = '/' . $app . '/' . $app . '.php';
        if ($userClear === true) {
            // load apps
            if (file_exists($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . $appPath)) {
                require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . $appPath);
                return true;
            } else if (file_exists($sdmassembler->sdmCoreGetUserAppDirectoryPath() . $appPath)) {
                require($sdmassembler->sdmCoreGetUserAppDirectoryPath() . $appPath);
                return true;
            }
            // failed to load app | log error to error log so admin can debug problem.
            error_log('Warning: SdmAssembler() could not load app "' . $app . '". Make sure the app is installed in either the core or user app directory and that it is configured properly. This error most likely occured becuase the assmebler could not locate the "' . $app . '" app at either "' . $sdmassembler->sdmCoreGetCoreAppDirectoryPath() . $appPath . '" or "' . $sdmassembler->sdmCoreGetUserAppDirectoryPath() . $appPath . '"');
            return false;
        }
        // user does not have permission to use this app
        $sdmassembler->sdmAssemblerIncorporateAppOutput('You do not have permission to be here.', array('incpages' => array($app)));
        return 'accessDenied';
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
     * @return object <p style="font-size:9px;">The DataObject with or without app modifications incorporated.
     * The DataObject may be returned without modification for a number of reasons including the user not
     * having permission to view the app, the requested page being found in the options:ignorepages array or
     * not being found in the options:incpages array, or a number of other factors.</p>
     */
    public function sdmAssemblerIncorporateAppOutput($output, array $options = array())
    {
        // determine the requested page
        $requestedPage = $this->sdmCoreDetermineRequestedPage();
        // filter options array to insure it's integrity
        $this->filterOptionsArray($options);
        // first we check if app output is restricted to certain roles. if it is we check that current user role matches one of the valid roles for the app. If the special 'all' role is in the $options['roles'] array then all users see the app output
        $validUser = (in_array($this->SdmGatekeeperDetermineUserRole(), $options['roles']) || in_array('all', $options['roles']) ? true : false);
        // make sure useer has permission to use this app. if user does NOT, then return the DataObject without modification.
        if ($validUser !== true) {
            return $this->DataObject;
        }
        // Check that $requested page was found in core or listed in the options:incpages array
        $pageFoundInCore = in_array($requestedPage, $this->sdmCoreListAvailablePages());
        $pageFoundInIncpages = in_array($requestedPage, $options['incpages']);
        if ($pageFoundInCore === false && $pageFoundInIncpages === false) {
            return $this->DataObject;
        }
        /* DATAOBJECT check | Make sure the properties we are modifying exist to prevent throwing any PHP errors */
        // Insure that page, weather in core or app generated, is accessible via the DataObject.
        $this->sdmAssemblerPrepareAppGeneratedPage();
        // Insure the target wrapper is accessible via the DataObject.
        $this->sdmAssemblerPrepareTargetWrapper($options);
        // make sure requested page is not in the ignorepages array, if it is return DataObject without modification.
        if (in_array($requestedPage, $options['ignorepages'])) {
            return $this->DataObject;
        }
        // Only incorporate app output if requested page matches one of the items in incpages
        if (in_array($requestedPage, $options['incpages'], true)) {
            switch ($options['incmethod']) {
                case 'prepend':
                    $this->DataObject->content->$requestedPage->$options['wrapper'] = $output . $this->DataObject->content->$requestedPage->$options['wrapper'];
                    break;
                case 'overwrite':
                    $this->DataObject->content->$requestedPage->$options['wrapper'] = $output;
                    break;
                default:
                    $this->DataObject->content->$requestedPage->$options['wrapper'] .= $output;
                    break;
            }
        }

        // return the modified DataObject.
        return $this->DataObject;
    }

    /**
     * <p>Insures the integrity of the $options array before it is used by sdmAssemblerIncorporateAppOutput().</p><br>
     * <p><b>NOTE: This method should only be called internally by sdmAssemblerIncorporateAppOutput(), it is NOT
     * designed for use by other components.</b></p>
     * @param array $options <p>The options array as it was passed to sdmAssemblerIncorporateAppOutput().</p>
     * @return array <p>The filtered options array. This method handles the options array by reference so there is
     * no need to assign it's return value to a var in sdmAssemblerIncorporateAppOutput(). Just call...
     * $this->filterOptionsArray($options)
     * ...and the options array will be filtered.</p>
     */
    final private function filterOptionsArray(&$options)
    {
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
             *   sdmAssemblerIncorporateAppOutput($this->DataObject, $output, array('incpages' => array());// app out put will NOT be incorporated into any pages because incpages is empty
             *   sdmAssemblerIncorporateAppOutput($this->DataObject, $output);// app out put will be incorporated into all pages because incpages does not exist, and will therefore be created and configured with pre-determined internal default values
             */
            $pages = $this->sdmCoreListAvailablePages();
            $enabledApps = json_decode(json_encode($this->sdmCoreDetermineEnabledApps()), true);
            $options['incpages'] = array_merge($pages, $enabledApps);
        }
        // if $options['roles'] is not set then we assume all users should see this app output and we add the special 'all' value to the $otpions['roles'] array, if $options['roles'] is empty we assume no users can see this app output.
        if (!isset($options['roles'])) {
            $options['roles'] = array('all');
        }
        return $options;
    }

    /**
     * <p>This method prepares app generated #pages, for display by insuring they
     * exist in the DataObject.</p><br>
     * <p><i>#pages used by an app to display app output will not necessarily exist in core,
     * so to insure they can be referenced via the DataObject we need to manually add them
     * to the DataObject if they do not already exist in the DataObject.</i></p><br>
     * <p><b>NOTE: This method should only be called internally by sdmAssemblerIncorporateAppOutput(), it is NOT
     * designed for use by other components.</b></p>
     * @return bool <p>true if page exists or was successfully added to the DataObject,
     * false if neither of the previous two statements is true.</p>
     */
    final private function sdmAssemblerPrepareAppGeneratedPage()
    {
        $requestedPage = $this->sdmCoreDetermineRequestedPage();
        // if no page exists for app in the CORE, then create a placeholder object for it to avoid PHP Errors, Notices, and Warnings
        if (!isset($this->DataObject->content->$requestedPage)) {
            $this->DataObject->content->$requestedPage = new stdClass();
        }
        $status = (isset($this->DataObject->content->$requestedPage) ? true : false);
        return $status;
    }

    /**
     * <p>This method insures the targeted wrapper actually exists in the DataObject.
     * If it does not it is created so that it can be accessed via the DataObject.</p><br>
     * <p><b>NOTE: This method should only be called internally by sdmAssemblerIncorporateAppOutput(), it is NOT
     * designed for use by other components.</b></p>
     * @param array $options <p>The $options array passed to sdmAssemblerIncorporateAppOutput().</p>
     * @return
     */
    final private function sdmAssemblerPrepareTargetWrapper($options)
    {
        $requestedPage = $this->sdmCoreDetermineRequestedPage();
        // if target wrapper doesn't exist then create a placeholder for it to avoid any PHP Errors, Notices, or Warnings
        if (!isset($this->DataObject->content->$requestedPage->$options['wrapper'])) {
            $this->DataObject->content->$requestedPage->$options['wrapper'] = '';
        }
        $status = (isset($this->DataObject->content->$requestedPage->$options['wrapper']) ? true : false);
        return true;
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
    private function sdmAssemblerPreparePageForDisplay($page)
    {
        foreach ($page as $wrapper => $content) {
            $page->$wrapper = html_entity_decode($content, ENT_HTML5, 'UTF-8');
        }
        return $page;
    }

    /**
     * Returns <p>The required closing HTML tags for the page.</p>
     * @return <p>string The required HTML closing tags as a string.</p>
     */
    public function sdmAssemblerAssembleHtmlRequiredClosingTags()
    {
        return '
    <!--This site was built using the SDM CMS content management system which was designed and developed by Sevi Donnelly Foreman in the year 2014.-->
    <!--To contact the developer of the SDM CMS write to sdmwebsdm@gmail.com.-->
    <!--Note: Sevi is not necessarily the author of this site, he is just the developer of Content Management System that is used to build/maintain this site.-->
    </body>
    </html>
    ';
    }

    /**
     * <p>Assembles the html content for a given $wrapper and returns it as a string. This method
     * is meant to be called from within a themes page.php file.</p>
     * @param string $wrapper <p>The wrapper to assemble html</p>
     * @return type
     */
    public function sdmAssemblerGetContentHtml($wrapper)
    {
        // initialize the SdmNms so we can add our menus to the page.
        // $nms = new SdmNms();
        $page = $this->sdmCoreDetermineRequestedPage();
        $wrapperAssembledContent = (isset($this->DataObject->content->$page->$wrapper) ? $this->DataObject->content->$page->$wrapper : '<!-- ' . $wrapper . ' placeholder -->');
        $content = $this->sdmNmsGetWrapperMenusHtml($wrapper, $wrapperAssembledContent);
        return $content;
    }

}

