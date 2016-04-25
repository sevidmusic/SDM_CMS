<?php

/**
 * The SdmAssembler() is responsible for loading and assembling a page.
 * It is also responsible for incorporating output from core and user apps into
 * a page.
 *
 * @author Sevi Donnelly Foreman
 *
 */
class SdmAssembler extends SdmNms
{
    /**
     * Assembles the html header for a page and incorporates stylesheets, scripts, and meta header
     * properties defined in enabled user and core app .as files, and in the current theme's .as file,
     * into the html header.
     *
     * Header properties defined in app .as files will always be assembled before header properties
     * defined in current theme's .as file because app header properties must take precedence over the
     * current theme's header properties. Additionally, header properties defined in core app .as files
     * will always be assembled before header properties defined in user app .as files because core app
     * header properties must take precedence over user app header properties.
     *
     * The order app header properties are assembled relative to other apps depends on the order
     * in which the apps were enabled. The most recent app to be enabled will have it's header
     * properties assembled last. So if apps A and C were enabled before app B then app B's
     * header properties will be assembled after apps A and C have their header properties
     * assembled. However, if app A and B and C were enabled at the same time then the order
     * is alphabetical, so app B will have its header properties assembled after app A and
     * before app C.
     *
     * @return string The html header for the page.
     *
     */
    public function sdmAssemblerAssembleHtmlHeader()
    {
        return '
            <!DOCTYPE html>
            <html>
                <head>
                    <title>' . (isset($_GET['page']) ? ucfirst($_GET['page']) : ucfirst('homepage')) . '</title>
                    <!-- Header properties defined by enabled apps: -->
                    ' . $this->sdmAssemblerAssembleEnabledAppProps() . '
                    <!-- Header properties defined by current theme: -->
                    ' . $this->sdmAssemblerAssembleCurrentThemeProps() . '
                    <!-- Set base url. -->
                    <base href="' . $this->sdmCoreGetRootDirectoryUrl() . '" target="_self">
                </head>
            <body class="' . $this->sdmCoreDetermineCurrentTheme() . '">
            ';
    }

    /**
     * Assembles the header properties defined in a .as file
     * for all enabled apps that provide a .as file.
     *
     * @return string Html formatted string of link, script, and meta tags for any stylesheets, scripts, and meta
     * header properties defined in any enabled apps .as file.
     *
     */
    final private function sdmAssemblerAssembleEnabledAppProps()
    {
        /* Initialize $appScriptProps var. This will store the assembled header properties. */
        $appScriptProps = '';

        /* Properties to assemble. */
        $properties = array('meta', 'scripts', 'stylesheets');

        /* Sources to try and assemble header properties from. i.e., core or user apps. */
        $sources = array('coreApp' => $properties, 'userApp' => $properties);

        /* Enabled apps to try and assemble header properties for. */
        $enabledApps = $this->sdmCoreDetermineEnabledApps();

        /* Assemble header properties for enabled core and user apps. **/
        foreach ($enabledApps as $sourceName) {
            /* Try to assemble header properties from each $source. */
            foreach ($sources as $source => $properties) {
                /* Store the current state of $appScriptProps to test against later. */
                $initAppScripts = $appScriptProps;

                /* Assemble properties */
                foreach ($properties as $targetProperty) {
                    /* Try to assemble the $targetProperty for the current app. */
                    $assembledHeaderProperties = $this->sdmAssemblerAssembleHeaderProperties($targetProperty, $source, $sourceName);

                    /* If $target property was successfully assembled append it to $appScriptProps, otherwise
                     append an empty string. */
                    $appScriptProps .= ($assembledHeaderProperties === false ? '' : $assembledHeaderProperties);
                }

                /* If properties were assembled from this $source for this app then move onto the next app.
                   Otherwise continue the loop in order to try next $source. */
                if ($initAppScripts !== $appScriptProps) {
                    break 1;
                }
            }
        }
        return $appScriptProps;
    }

    /**
     * Assembles link script and meta tags for header properties defined in a specified theme or app's .as file.
     *
     * By default this method assembles header properties for the current theme.
     *
     * This method will return false on failure.
     *
     * Examples:
     *
     *   // assembles 'stylesheets' header properties for current theme because $source and $sourceName are not set.
     *
     *   sdmAssemblerAssembleHeaderProperties('stylesheets');
     *
     *   // assembles 'stylesheets' header properties for the sdmResponsive theme.
     *
     *   sdmAssemblerAssembleHeaderProperties('stylesheets', 'theme', 'sdmResponsive');
     *
     *   // assembles 'scripts' header properties for the contentManager core app.
     *
     *   sdmAssemblerAssembleHeaderProperties('scripts', 'coreApp', 'contentManager');
     *
     *   // assembles 'meta' header properties for the helloWorld user app.
     *
     *   sdmAssemblerAssembleHeaderProperties('meta', 'userApp', 'helloWorld');
     *
     * @param string $targetProperty Property to read. (options: stylesheets, scripts, or meta)
     *
     * @param string $source The type of component to assemble header properties for. (options: theme, userApp,
     *                       or coreApp).
     *
     *                       IMPORTANT: If $source is set then $sourceName must also be set.
     *
     * @param string $sourceName The name of the theme or app whose .as file we are reading header properties from.
     *
     *                       IMPORTANT: $sourceName must be set if $source is set.
     *
     * @return string Html formatted string of link script and meta tags for properties defined in specified
     *                $sourceName's .as file. Returns false on failure.
     *
     */
    private function sdmAssemblerAssembleHeaderProperties($targetProperty, $source = null, $sourceName = null)
    {
        /* Initialize $html var. */
        $html = $this->sdmAssemblerAssembleInitialHeaderPropertyHtml($targetProperty, $source, $sourceName);

        /* Store initial $html value so a check can be performed later to see if anything was appended
         to $html, if nothing was appended to $html by the end of this method then the attempt to
         load the .as file properties failed. */
        $initHtml = $html;

        /* Attempt to read header properties from .as file if it exists. If $source and $sourceName aren't specified
         then the current themes .as file will be read if it exists. */
        $properties = $this->sdmAssemblerGetAsProperty($targetProperty, $source, $sourceName);

        /* Create array of 'bad values' to check against prior to assembling the header properties html. */
        $badValues = array('none', '', 'n/a', 'null', null, 'false', false);

        /* Make sure $properties array is not false and is not empty. */
        if ($properties !== false && !empty($properties) === true) {
            /* Determine url to components root directory. | Used in assembly of link and script tags for
             property values defined in 'stylesheets' and 'scripts' header properties respectively. */
            $componentUrl = trim($this->sdmAssemblerDetermineComponentUrl($source, $sourceName));

            /* Begin assembling  link script and meta tags for each of the header $properties. */
            foreach ($properties as $propertyValue) {
                /* Only assemble header property html if current $propertyValue does not exist
                 in our $badValues array */
                if (!in_array($propertyValue, $badValues)) {
                    switch ($targetProperty) {
                        case 'stylesheets':
                            $html .= '<link rel="stylesheet" type="text/css" href="' . $componentUrl . '/' . trim($propertyValue) . '.css">';
                            break;
                        case 'scripts':
                            $html .= '<script src="' . $componentUrl . '/' . trim($propertyValue) . '.js"></script>';
                            break;
                        case 'meta':
                            $html .= '<meta ' . trim($propertyValue) . '>';
                            break;
                        default:
                            $msg = 'Value "' . $propertyValue . '" from .as file property "' . $targetProperty . '" was
                            not loaded, custom .as properties are not recognized. | Source:  ' . $sourceName;
                            error_log($msg);
                            break;
                    }
                }
            }

        }
        return ($html === $initHtml ? false : $html);
    }

    /**
     * Assembles the initial header property html. By default this method generates the initial header property
     * html for the current theme.
     *
     * Specifically, assembles an html code comment in the following format to help make html markup generated
     * for a page more readable by making it clear what component, app or theme, is defining the $targetProperty
     * being assembled:
     *
     *     <!-- $source $sourceName $targetProperty-->
     *
     * @param string $targetProperty The target property. (options: stylesheets, scripts, or meta)
     *
     * @param string $source The type of component. (options: theme, userApp,
     *                       or coreApp).
     *
     *                       IMPORTANT: If $source is set then $sourceName must also be set.
     *
     * @param string $sourceName The name of the relevant theme or app.
     *
     *                       IMPORTANT: $sourceName must be set if $source is set.
     *
     * @return string Initial header property html.
     */
    final private function sdmAssemblerAssembleInitialHeaderPropertyHtml($targetProperty, $source, $sourceName)
    {
        return '<!-- ' . ($source === null ? $this->sdmCoreDetermineCurrentTheme() . ' Theme ' . $targetProperty : ($source === 'userApp' ? 'User App' : ($source === 'coreApp' ? 'Core App' : 'Theme')) . ' ' . $sourceName . ' ' . $targetProperty) . ' -->';
    }

    /**
     * Returns an array of values for a specified property defined in a
     * specified theme or app's .as file.
     *
     * By default properties are retrieved from the current themes .as file.
     *
     * @param string $property The property whose values should be returned. (options: stylesheets, scripts, meta)
     *
     * @param string $source The type of component (options: theme, userApp, or coreApp).
     *
     *                    IMPORTANT: If $source is set then $sourceName must also be set.
     *
     * @param string $sourceName The name of the relevant theme or app.
     *
     *                       IMPORTANT: $sourceName must be set if $source is set.
     *
     * @return array|bool An array of values for the specified $property, or false on failure.
     *
     *               Note: false will be returned if
     *               any of the following are true:
     *
     *               - If .as file does not exist.
     *
     *               - If property is not set in .as file.
     *
     *               - If property listed in .as file but not defined.
     *
     *               - If the method failed to get the property values far any other reason.
     *
     */
    private function sdmAssemblerGetAsProperty($property, $source = null, $sourceName = null)
    {
        /* Load .as file. */
        $asFileArray = $this->sdmAssemblerLoadAsFile($source, $sourceName);

        /* Retrieve properties defined in the loaded .as file. */
        $properties = $this->sdmAssemblerRetrieveAsPropertyValues($property, $asFileArray);

        /* Return $properties retrieved from the loaded .as file, Return false if no properties were retrieved. */
        return ($asFileArray === false || isset($properties) !== true ? false : $properties);
    }

    /***
     * Load a .as file from a specified app or theme and read it into an array.
     *
     * By default this method will attempt to load the current theme's .as file if provided.
     *
     * @param string $source The type of component. (options: theme, userApp,
     *                       or coreApp).
     *
     *                       IMPORTANT: If $source is set then $sourceName must also be set.
     *
     * @param string $sourceName The name of the relevant theme or app. Defaults to
     *                           name of current theme.
     *
     *                       IMPORTANT: $sourceName must be set if $source is set.
     *
     * @return array|bool An array representing the data in the .as file, or false on failure.
     *
     */
    final private function sdmAssemblerLoadAsFile($source, $sourceName)
    {
        /* If $sourceName is not defined default to current theme. */
        if ($sourceName === null) {
            $sourceName = $this->sdmCoreDetermineCurrentTheme();
        }
        switch ($source) {
            case 'theme':
                $path = $this->sdmCoreGetThemesDirectoryPath();
                break;
            case 'userApp':
                $path = $this->sdmCoreGetUserAppDirectoryPath();
                break;
            case 'coreApp':
                $path = $this->sdmCoreGetCoreAppDirectoryPath();
                break;
            default:
                $path = str_replace('/' . $sourceName, '', $this->sdmCoreGetCurrentThemeDirectoryPath());
                break;
        }
        /* Build file path to our .as file. */
        $filePath = $path . '/' . $sourceName . '/' . $sourceName . '.as';
        return (file_exists($filePath) === true ? file($filePath) : false);
    }

    /**
     * Retrieves a specified properties values from the array returned
     * by sdmAssemblerLoadAsFile(). This method is for internal use only.
     *
     * @param string $property The property to retrieve values from.
     *
     * @param array|bool $asFileArray The return value from call to sdmAssemblerLoadAsFile().
     *                                This will be either an array or the boolean value false.
     *
     * @return array|bool Array of property values for the specified property, or false on failure.
     *
     */
    final private function sdmAssemblerRetrieveAsPropertyValues($property, $asFileArray)
    {
        if ($asFileArray !== false) {
            /* Loop through array. i.e., loop through each line of the .as file. */
            foreach ($asFileArray as $line) {
                /* Check if current $line matches $property. */
                if (strstr($line, '=', true) === $property) {
                    switch ($property) {
                        case 'meta':
                            /* Extract meta data. Because the definition of a meta property is more complex
                             it has to be retrieved separately. */
                            $properties = $this->sdmAssemblerExtractMeta($line);
                            break;
                        default:
                            /* Retrieve stylesheet and scripts properties. */
                            $properties = explode(',', $this->sdmCoreStrSlice($line, '=', ';'));
                    }

                    /* Return any properties that were retrieved. */
                    return $properties;
                }
            }
        }

        /* Return false if no properties were retrieved. */
        return false;
    }

    /**
     * Responsible for extracting and interpreting the meta data defined in a .as meta property string.
     *
     * @param $metaProperty String The meta property string to extract and interpret meta data from.
     *
     * @return array Returns an array of strings that make up the internal structure of the meta tags
     *               for a page.
     */
    final private function sdmAssemblerExtractMeta($metaPropertyString)
    {
        /* Extract characters between the first '=' and the last ';' to get the property values */
        $metaProps = $this->sdmCoreStrSlice($metaPropertyString, '=', ';');

        /* Explode property values into an array, splitting string by '],' */
        $metaData = explode('],', $metaProps);

        /* Initialize $meta array. */
        $meta = array();

        /* Assemble internal meta structures */
        foreach ($metaData as $data) {
            /* Remove '[' and ']' from each piece of meta $data. */
            $data = str_replace(['[', ']'], '', $data);
            /* Replace ',' with '"' in each piece of meta $data. */
            $data = str_replace(',', '"', $data);
            /* Replace ':' with '=' in each piece of meta $data. */
            $data = str_replace(':', '="', $data);
            /* Replace '|' with ' ' in each piece of meta $data. */
            $data = str_replace('|', ' ', $data) . '"';
            /* Add extracted meta $data to $meta array. */
            $meta[] = $data;
        }
        /* Return $meta array. */
        return $meta;
    }

    /**
     * Determines url to a specified theme or apps root directory.
     *
     * By default this method returns url to the current theme's root directory.
     *
     * @param string $source The type of component (options: theme, userApp, or coreApp).
     *
     *                    IMPORTANT: If $source is set then $sourceName must also be set.
     *
     * @param string $sourceName The name of the relevant theme or app.
     *
     *                    IMPORTANT: $sourceName must be set if $source is set.
     *
     * @return null|string The url to the specified app or theme's root directory. Returns null on failure.
     *
     */
    final private function sdmAssemblerDetermineComponentUrl($source, $sourceName)
    {
        return ($source === null ? $this->sdmCoreGetCurrentThemeDirectoryUrl() : ($source === 'theme' ? $this->sdmCoreGetThemesDirectoryUrl() . '/' . $sourceName : ($source === 'userApp' ? $this->sdmCoreGetUserAppDirectoryUrl() . '/' . $sourceName : ($source === 'coreApp' ? $this->sdmCoreGetCoreAppDirectoryUrl() . '/' . $sourceName : null))));
    }

    /**
     * Assembles the html tags for the link, script, and meta properties defined in the current theme's .as file
     * if it provides one.
     *
     * @return string  String of link, script, and meta tags for any stylesheets, scripts, and
     * meta properties defined in the current theme's .as file if provided, or false on failure.
     *
     */

    final private function sdmAssemblerAssembleCurrentThemeProps()
    {
        /* Array of valid properties to assemble. */
        $properties = array('meta', 'stylesheets', 'scripts');

        /* Initialize $themeProps which will store any successfully assembled header properties. */
        $themeProps = '';

        /* Assemble current theme's header properties. */
        foreach ($properties as $property) {
            $themeProps .= $this->sdmAssemblerAssembleHeaderProperties($property);
        }

        /* Return the assembled properties or false on failure. */
        return ($themeProps === false ? false : $themeProps);
    }

    /**
     * @return bool
     */
    public function sdmAssemblerLoadTheme()
    {
        /* Store current assembler in variable so themes can access it. */
        $sdmassembler = $this;

        /* Require theme file defining page's html structure. */
        $themeDirectoryListing = $this->sdmCoreGetDirectoryListing('', 'CURRENT_THEME');
        switch (in_array(trim($this->sdmCoreDetermineRequestedPage()) . '.php', $themeDirectoryListing, true) === true) {
            case true:
                /* Require our current theme's page.php. */
                require_once($this->sdmCoreGetCurrentThemeDirectoryPath() . '/' . trim($this->sdmCoreDetermineRequestedPage()) . '.php');
                break;
            default:
                /* Require our current theme's page.php. */
                require_once($this->sdmCoreGetCurrentThemeDirectoryPath() . '/page.php');
                break;
        }
        return true;
    }

    /**
     * Loads and assembles the content for the requested page and updates the current DataObject.
     *
     * If requested page exists or is dynamically generated by an app then this method will
     * update the current DataObject's content object with the newly assembled content.
     *
     * If the requested page does not exist or is not dynamically generated by an app then this
     * method will update the current DataObject's content object with an internally generated
     * "Page Not Found" message and will log a bad request to the badRequestsLog.
     *
     * @return bool Returns true if requested page exists or is dynamically generated by an app.
     *              Will return false if requested page does not exist or is not dynamically
     *              generated by an app.
     */
    public function sdmAssemblerLoadAndAssembleContent()
    {
        /* Determine requested page. */
        $requestedPage = $this->sdmCoreDetermineRequestedPage();

        /* Load and assemble enabled apps. */
        $this->sdmAssemblerLoadApps();

        /* Cast $sdmAssemblerDataObject->content->$requestedPage to an array so PHP's empty() can be used
           to test if there is any content to be assembled. empty() works better then isset() because the
           DataObject's content object may exist with no properties which would cause an isset() check to
           return true even if there isn't any content to be assembled. */
        $requestedPageContent = (array)$this->DataObject->content->$requestedPage;

        /* Make sure page exists in DataObject or as a dynamically generated app page by checking
           if the $requestedPageContent array is empty. If the page exists update the current DataObject
           with assembled content, if not, then log the bad request to the bad requests log, generate
           a page not found page, and update the current DataObject. */
        switch (empty($requestedPageContent)) {
            case false:
                /* Update DataObject with assembled content. */
                $this->DataObject->content->$requestedPage = $this->sdmAssemblerPreparePageForDisplay();
                return true;
            default:
                /* Assemble Bad Request Message */
                $badRequestId = chr(rand(65, 90)) . rand(10, 99) . chr(rand(65, 90)) . rand(10, 99);
                $badRequestDate = date('d-M-Y H:i:s e');
                $badRequestUrl = $this->sdmCoreGetRootDirectoryUrl() . '/index.php?' . $_SERVER['QUERY_STRING'];
                $truncatedBadRequestUrl = (strlen($badRequestUrl) > 112 ? substr($badRequestUrl, 0, 112) . '...' : $badRequestUrl);
                $linkedByInfo = (isset($_GET['linkedByMenu']) === true ? 'Request Origin: Internal' . PHP_EOL . '- Menu:' . $_GET['linkedByMenu'] . PHP_EOL . (isset($_GET['linkedByMenuItem']) ? '- Menu Item: ' . $_GET['linkedByMenuItem'] : 'menu item unknown') : (isset($_GET['linkedBy']) === true ? 'Request Origin: ' . $_GET['linkedBy'] : 'Request Origin: Unknown'));
                $errorMessage = '----- BAD REQUEST [' . $badRequestDate . '] -----' . PHP_EOL .
                    'Bad request id: ' . $badRequestId . PHP_EOL .
                    'Requested Page: ' . $requestedPage . PHP_EOL .
                    'Requested Url: ' . $badRequestUrl . PHP_EOL .
                    'Request Made by User: ' . 'anonymous' . PHP_EOL .
                    $linkedByInfo . PHP_EOL .
                    '---------------------------------------------------------------' . PHP_EOL;

                /* Log bad request to our badRequestsLog.log file. */
                error_log($errorMessage, 3, $this->sdmCoreGetCoreDirectoryPath() . '/logs/badRequestsLog.log');

                /* Update DataObject with "Page Not Found" content. */
                $this->DataObject->content->$requestedPage = json_decode(json_encode(['main_content' => '<p>The requested page at <b>' . $this->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $requestedPage . '</b> could not be found. Check the url to for typos. If error persists and your sure this content should exist contact the site admin  at (@TODO:DYNAMICALLY PLACE ADMIN EMAIL HERE) to report the error.</p><p>' . 'Bad request id: ' . $badRequestId . '</p><p>' . 'Requested Page: ' . $requestedPage . '</p><p>Requested Url <i>(trimmed for display)</i>: ' . $truncatedBadRequestUrl . '</p>']));
                return false;
        }
    }

    /**
     * Loads all enabled apps.
     *
     * @return bool Returns true if apps loaded successfully, or false if any apps failed to load.
     */
    private function sdmAssemblerLoadApps()
    {
        $enabledApps = $this->sdmCoreDetermineEnabledApps();
        $status = array();
        foreach ($enabledApps as $app) {
            $status[] = $this->sdmAssemblerLoadApp($app);
        }
        return (in_array(false, $status, true));
    }

    /**
     * Loads a specific core or user app. This method should only be used internally by
     * the SdmAssembler()'s sdmAssemblerLoadApps() method.
     *
     * @param string $app The name of the app to load.
     *
     * @return mixed Returns true if app was loaded, or false if app could not be loaded as a result
     * of an error. Additionally, this method will return the string 'accessDenied' if app was not loaded
     * as a result of user not having sufficient privileges to use app.
     */
    private function sdmAssemblerLoadApp($app)
    {
        /* Create an appropriately named reference to $this so apps can utilize the properties and methods
         of the SdmAssembler() without having to instantiate their own SdmAssembler() object. */
        $sdmassembler = $this;

        /* Assemble relative path to app. */
        $appPath = '/' . $app . '/' . $app . '.php';

        /* Determine if user has permission to use app. */
        $userClear = $this->sdmGatekeeperUserClearToUseApp($app);

        /* If user clear to use app then attempt to load the app, otherwise deny user access. */
        if ($userClear === true) {
            /* load apps */
            if (file_exists($this->sdmCoreGetCoreAppDirectoryPath() . $appPath)) {
                require_once($this->sdmCoreGetCoreAppDirectoryPath() . $appPath);
                return true;
            } else if (file_exists($this->sdmCoreGetUserAppDirectoryPath() . $appPath)) {
                require($this->sdmCoreGetUserAppDirectoryPath() . $appPath);
                return true;
            }

            /* App failed to load, log error. */
            error_log('Warning: SdmAssembler() could not load app "' . $app . '". Make sure the app is installed
                in either the core or user app directory and that it is configured properly. This error most likely
                occurred because the assembler could not locate the "' . $app . '" app at either "' .
                $this->sdmCoreGetCoreAppDirectoryPath() . $appPath . '" or "' .
                $this->sdmCoreGetUserAppDirectoryPath() . $appPath . '"');
            return false;
        }

        /* User does not have permission to use this app, deny access. */
        $options = array('incmethod' => 'overwrite', 'incpages' => array($app));
        $sdmassembler->sdmAssemblerIncorporateAppOutput('You do not have permission to be here.', $options);
        return 'accessDenied';
    }

    /**
     * Incorporates app $output into the page.
     *
     * This method is intended for use by core and user apps. It provides
     * a simple method for incorporating an app's $output into the page.
     *
     * It is ok to call this method multiple times within an app.
     *
     * This method will return true if app $output was successfully incorporated,
     * or false if app $output could not be incorporated.
     *
     * @param string $output A plain text or html string to be used as the apps output.
     *
     * @param array $options Array of options that determine how an app's $output is incorporated.
     *                       If not specified, then the app $output will be incorporated into all pages,
     *                       will be accessible to all users, and will be appended to the 'main_content'
     *                       wrapper of each page.
     *
     * Overview of $options array:
     *
     *   'wrapper' :     The content wrapper the app $output is to be incorporated into.
     *                   Defaults to 'main_content'.
     *
     *   'incmethod' :   Determines how app $output should be incorporated. Options
     *                   for 'incmethod' are append, prepend, and overwrite.
     *                   Defaults to append.
     *
     *   'incpages' :    Array of pages to incorporate the app output into. If set, then app
     *                   $output will be incorporated exclusively into the pages in this
     *                   array. If not set, then the app output will be incorporated
     *                   into all pages. If a page in this array does not already exist in the
     *                   DataObject then it will be generated dynamically.
     *
     *                   NOTE: If an empty array is passed to 'incpages' then the app output will not be incorporated
     *                   into any page. Passing an empty array to 'incpages' is basically the same as passing the
     *                   special value 'all' to the 'ignorepages' option.
     *
     *                   i.e,
     *
     *                   To incorporate app output into all pages DO NOT set the 'incpages' option:
     *
     *                   e.g.,
     *                   sdmAssemblerIncorporateAppOutput($output);
     *
     *                   To incorporate app output into specific pages pass an array of the pages
     *                   that should incorporate the app output to the 'incpages' option.
     *
     *                   e.g.,
     *                   sdmAssemblerIncorporateAppOutput($output, array('incpages'=> array('page1', 'page2', ...)));
     *
     *                   Remember, passing an empty array to 'incpages' is the same as passing
     *                   all pages to the 'ignorepages' option.
     *
     *                   e.g.,
     *
     *                   sdmAssemblerIncorporateAppOutput($output, array('incpages'=> array()));
     *
     *                   // is basically the same as //
     *
     *                   sdmAssemblerIncorporateAppOutput($output, array('ignorepages'=> array('all')));
     *
     *   'ignorepages' : Array of pages that should not incorporate the app output. Passing the special
     *                   value 'all' will result in the app output not being incorporated into any
     *                   page. This parameter is useful while developing apps so the developer can
     *                   enable the app being developed without having the output actually get
     *                   incorporated into any page.
     *
     *                   NOTE: If a page is found in both the 'incpages' and 'ignorepages' arrays then
     *                   the app output will be ignored on that page. This is for security, best to assume
     *                   in such a case that the developer meant to ignore a page if the developer passes
     *                   a page to both the 'incpages' and 'ignorepages' arrays.
     *
     *   'roles' :       Array of roles that have permission to view app output. Passing the special
     *                   value 'all' will give all users permission to view app output. Current options
     *                   for roles are root, basicUser, and the special value all. If an empty array is
     *                   passed it will be assumed that no users can see this app output.
     *
     *                   // All users will have permission to view this app output. //
     *                   sdmAssemblerIncorporateAppOutput($output, array('roles' => array('all')));
     *
     *                   // No users will have permission to view this app output because 'roles' is empty. //
     *                   sdmAssemblerIncorporateAppOutput($output, array('roles' => array()));
     *
     *
     * @return bool True if output was incorporated, or false on failure.
     */
    public function sdmAssemblerIncorporateAppOutput($output, array $options = array())
    {
        /* Determine the requested page. */
        $requestedPage = $this->sdmCoreDetermineRequestedPage();

        /* Filter options array to insure it's integrity. */
        $this->filterOptionsArray($options);

        /* Make sure user has permission to use this app. If user does not have permission, then return false. */
        if ($this->sdmAssemblerUserCanUseApp($options) !== true) {
            return false;
        }

        /* Check that $requested page was found in core or listed in the $options['incpages'] array */
        $pageFoundInCore = in_array($requestedPage, $this->sdmCoreDetermineAvailablePages(), true);
        $pageFoundInIncpages = in_array($requestedPage, $options['incpages'], true);
        if ($pageFoundInCore === false && $pageFoundInIncpages === false) {
            return false;
        }

        /* Make sure requested page is not in the $options['ignorepages'] array, If it is return false. */
        if (in_array($requestedPage, $options['ignorepages'], true) || in_array('all', $options['ignorepages'], true)) {
            return false;
        }

        /* Dynamically create app generated pages. These are pages specified in $options['incpages'] array that do
         not already exist in the DataObject. */
        $this->sdmAssemblerPrepareAppGeneratedPage();

        /* Insure the target wrapper is accessible via the DataObject. */
        $this->sdmAssemblerPrepareTargetWrapper($options);

        /* Only incorporate app output if requested page matches one of the items in incpages */
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

        /* return the modified DataObject. */
        return true;
    }

    /**
     * Insures the integrity of the $options array before it is used by sdmAssemblerIncorporateAppOutput().
     *
     * NOTE: This method should only be called internally by sdmAssemblerIncorporateAppOutput(), it is NOT
     * designed for use by other components.
     *
     * @param array $options The options array as it was passed to sdmAssemblerIncorporateAppOutput().
     *
     * @return array The filtered options array. This method handles the options array by reference so there is
     * no need to assign it's return value to a variable.
     */
    final private function filterOptionsArray(&$options)
    {
        /* Review $options array values to insure they exist. If they don't
        then they will be created and assigned a default value */

        /* If $options['wrapper'] is not set create it with value 'main_content'. */
        if (!isset($options['wrapper'])) {
            $options['wrapper'] = 'main_content';
        }

        /* If $options['incmethod'] is not set create it with value 'append'. */
        if (!isset($options['incmethod'])) {
            $options['incmethod'] = 'append';
        }

        /* If $options['ignorepages'] was not set create it and assign an empty array as it's value. */
        if (!isset($options['ignorepages'])) {
            $options['ignorepages'] = array();
        }

        /* If $options['incpages'] was not set create it and assign an array filled with all pages in
         the DataObject and with the names of all enabled apps. */
        if (!isset($options['incpages'])) {
            $pages = $this->sdmCoreDetermineAvailablePages();
            $enabledApps = json_decode(json_encode($this->sdmCoreDetermineEnabledApps()), true);
            $options['incpages'] = array_merge($pages, $enabledApps);
        }

        /* If $options['roles'] is not set create it and assign an array containing the special 'all' value.
         If $options['roles'] is empty it will be assumed that no users can see this app output. */
        if (!isset($options['roles'])) {
            $options['roles'] = array('all');
        }

        /* Return the filtered $options array. */
        return $options;
    }

    /**
     * Determines if a user has permission to use app based on
     * weather or not the current user's role is found in the
     * $options['roles'] array.
     *
     * If the special value 'all' is found in the $options['roles'] array
     * then all users will be given permission to use the app.
     *
     * This method should only be called internally by sdmAssemblerIncorporateAppOutput(), it is not
     * designed for use by other components.
     *
     * @param array $options The options array provided by the app.
     *
     * @return bool true if user has permission to use app, false if not.
     */
    final private function sdmAssemblerUserCanUseApp($options)
    {
        /* Return true if current user's role is found in the $options['roles'] array, or if the special 'all'
         value is found in the $options['roles'] array, otherwise return false. */
        return (in_array($this->sdmGatekeeperDetermineUserRole(), $options['roles'], true) || in_array('all', $options['roles'], true) ? true : false);
    }

    /**
     * This method dynamically adds a page to the DataObject during page assembly for enabled apps that generate
     * app output for a page that does not already exist in the DataObject.
     *
     * If the requested page already exists then this method will simply return true.
     *
     * This method should only be called internally by sdmAssemblerIncorporateAppOutput(), it is not
     * designed for use by other components.
     *
     * @return bool Returns true if page exists or was successfully added to the DataObject dynamically,
     * false if neither of the previous two statements is true.
     */
    final private function sdmAssemblerPrepareAppGeneratedPage()
    {
        $requestedPage = $this->sdmCoreDetermineRequestedPage();
        /* If requested page does not exist in the DataObject, then dynamically create it in the DataObject. */
        if (!isset($this->DataObject->content->$requestedPage)) {
            $this->DataObject->content->$requestedPage = new stdClass();
        }

        /* Make sure the page exists. */
        $status = (isset($this->DataObject->content->$requestedPage) ? true : false);

        /* Return true if page exists, false if page still does not exist. */
        return $status;
    }

    /**
     * This method insures the targeted wrapper actually exists for the requested page in the DataObject.
     *
     * If it does not it is dynamically created for the requested page in the DataObject.
     *
     * This method should only be called internally by sdmAssemblerIncorporateAppOutput(), it is not
     * designed for use by other components!
     *
     * @param array $options The $options array passed to sdmAssemblerIncorporateAppOutput().
     *
     * @return bool True if wrapper exists or was created successfully, false wrapper does not exist.
     */
    final private function sdmAssemblerPrepareTargetWrapper($options)
    {
        $requestedPage = $this->sdmCoreDetermineRequestedPage();
        /* if target wrapper doesn't exist then create a placeholder
        for it to avoid any PHP Errors, Notices, or Warnings */
        if (!isset($this->DataObject->content->$requestedPage->$options['wrapper'])) {
            $this->DataObject->content->$requestedPage->$options['wrapper'] = '';
        }
        $status = (isset($this->DataObject->content->$requestedPage->$options['wrapper']) ? true : false);
        return $status;
    }

    /**
     * Prepares the requested page for display in a theme. Basically, when a page is created it's content is filtered to insure
     * no bad chars are included and that the encoding is UTF-8.
     *
     * In order to insure html tags are interpreted as html we need to reverse some of the filtering that was done when
     * the page was created by the SdmCms() class. @see SdmCms::sdmCmsUpdateContent() for more information on how data
     * is filtered on page creation.
     *
     * This method should only be used internally by sdmAssemblerLoadAndAssembleContent().
     *
     * @return object The prepared page.
     *
     */
    private function sdmAssemblerPreparePageForDisplay()
    {
        $requestedPage = $this->sdmCoreDetermineRequestedPage();
        $page = $this->DataObject->content->$requestedPage;
        foreach ($page as $wrapper => $content) {
            $page->$wrapper = html_entity_decode($content, ENT_HTML5, 'UTF-8');
        }
        return $page;
    }

    /**
     * Returns the required closing html tags for the page.
     *
     * @return string The required HTML closing tags as a string.
     */
    public function sdmAssemblerAssembleHtmlRequiredClosingTags()
    {
        return '
    <!-- This site was built using the SDM CMS content management system which was
         designed and developed by Sevi Donnelly Foreman in the year 2014. -->
    <!-- To contact the developer of the SDM CMS write to sdmcmsinfo@gmail.com. -->
    <!-- Note: Sevi is not necessarily the author of this site, he is just the
         developer of the Content Management System that is used to build and maintain this site. -->
    </body>
    </html>
    ';
    }

    /**
     * Assembles the html content for a given $wrapper and returns it as a string of html. This method
     * is meant to be called from within a themes page.php file.
     *
     * @param string $wrapper The wrapper to assemble html for.
     *
     * @return string String of html for specified wrapper.
     */
    public function sdmAssemblerGetContentHtml($wrapper)
    {
        /* Determine requested page. */
        $page = $this->sdmCoreDetermineRequestedPage();

        /* Assemble the wrapper. */
        $wrapperAssembledContent = (isset($this->DataObject->content->$page->$wrapper) ? $this->DataObject->content->$page->$wrapper : '<!-- ' . $wrapper . ' placeholder -->');

        /* Get any menus that belong to this wrapper. */
        $content = $this->sdmNmsGetWrapperMenusHtml($wrapper, $wrapperAssembledContent);

        /* Return the assembled wrapper. */
        return $content;
    }

    /**
     * Creates an html element.
     * @param $content string The elements content. Can be a regular string, or a string of html.
     * @param $attributes array Associative array of attributes to define for the element defined
     *                          as follows:
     *
     *                          ['elementType']  string  The type of html element to create. Can be any valid html
     *                                                   element type.
     *
     *                          ['id']           string  The id to assign to the element.
     *
     *                          ['classes']      array   An array of classes to assign to the element.
     *
     *                          ['styles']       array   An array of inline styles to assign to the element.
     *                                                   Styles should be defined as follows: 'parameter: value'
     * @return string The html element.
     *
     */
    public function sdmAssemblerAssembleHtmlElement($content, $attributes)
    {
        /* Element Type. */
        $elementType = (isset($attributes['elementType']) && $attributes['elementType'] !== '' ? $attributes['elementType'] : 'div');

        /* Element Id. */
        $id = (isset($attributes['id']) && $attributes['id'] !== '' ? $attributes['id'] : '');

        /* Element classes. */
        $classes = (isset($attributes['classes']) && is_array($attributes['classes']) === true ? $attributes['classes'] : '');

        /* Element inline styles. */
        $styles = (isset($attributes['styles']) && is_array($attributes['styles']) === true ? $attributes['styles'] : '');

        /* Assemble id attribute string. */
        $idString = (isset($id) && $id !== '' ? ' id="' . $id . '"' : '');

        /* Assemble class attribute string. */
        $classesString = (!empty($classes) === true ? ' class="' . implode(' ', $classes) . '"' : '');

        /* Assemble style attribute string. */
        $stylesString = (!empty($styles) === true ? ' style="' . implode('; ', $styles) . '"' : '');

        /* Assemble element. */
        $element = '<' . $elementType . $idString . $classesString . $stylesString . '>' . $content . '</' . $elementType . '>';

        /* Return the element. | Wrap in PHP_EOL so each element has it's own line in html source code. */
        return PHP_EOL . $element . PHP_EOL;
    }

}

