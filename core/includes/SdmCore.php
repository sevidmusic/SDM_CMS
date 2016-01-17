<?php

/**
 * <p>The SDM_Core class provides mehtods for interacting with the SDM Core.</p>
 * <p>Additonally, the SDM_Core also is responsible for intializing, configuring, and
 * starting up the SDM CMS</p>
 *
 * @author seviforeman
 * @todo create a method that formats strings into camel case that can be used by developers and the CMS internally.
 * @todo create methods for use with a DATABASE so Admin can choose between storing site data in a json file or a DATABASE
 */
class SdmCore
{

    protected $DataObject;
    private $RootDirectoryPath;
    private $RootDirectoryUrl;
    private $CoreDirectoryPath;
    private $CoreDirectoryUrl;
    private $ConfigurationDirectoryPath;
    private $ConfigurationDirectoryUrl;
    private $IncludesDirectoryPath;
    private $ThemesDirectoryPath;
    private $ThemesDirectoryUrl;
    private $CurrentTheme;
    private $CurrentThemeDirectoryPath;
    private $CurrentThemeDirectoryUrl;
    private $UserAppDirectoryPath;
    private $UserAppDirectoryUrl;
    private $CoreAppDirectoryPath;
    private $CoreAppDirectoryUrl;
    private $DataDirectoryPath;
    private $DataDirectoryUrl;
    private $requestedPage;
    private $availablePages;

    final public function __construct()
    {
        // we need to do some special filetering to determine the Root Directory Path and Url
        $this->RootDirectoryPath = str_replace('/core/includes', '', __DIR__);
        /* We need to exclude from RootDirectoryUrl the index.php and reset.php files
         * as well as the final preceeding slashes (i.e., remove '/index.php' from http://example.com/index.php
         * This allows this property to be used more easily when concatinating strings to build paths and urls
         * in apps and themes, if we did not do this developers would have to remove these strings themselves
         * or end up with long strings of slahes and corrupted links | this was occuring during the development
         * of the SDM CMS when building urls in themes and apps, links would often end up looking like:
         *  'http://example.com//////index.php?page=homepage' <- this is not ok obviously
         * by removing the /FILENAME we solve this problem
         */
        // @todo: Find a way to autmoatically determine which .php files are in root, and ignore all of them
        $this->RootDirectoryUrl = str_replace(array('/index.php', '/reset.php', '/clearErrorLog.php'), '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        // the following properties rely on what is determined to be the Root Directory Path and URL
        $this->CoreDirectoryPath = $this->sdmCoreGetRootDirectoryPath() . '/core';
        $this->CoreDirectoryUrl = $this->sdmCoreGetRootDirectoryUrl() . '/core';
        $this->ConfigurationDirectoryPath = $this->sdmCoreGetCoreDirectoryPath() . '/config';
        $this->ConfigurationDirectoryUrl = $this->sdmCoreGetCoreDirectoryUrl() . '/config';
        $this->IncludesDirectoryPath = $this->sdmCoreGetCoreDirectoryPath() . '/includes';
        $this->ThemesDirectoryPath = $this->sdmCoreGetRootDirectoryPath() . '/themes';
        $this->ThemesDirectoryUrl = $this->sdmCoreGetRootDirectoryUrl() . '/themes';
        $this->UserAppDirectoryPath = $this->sdmCoreGetRootDirectoryPath() . '/apps';
        $this->UserAppDirectoryUrl = $this->sdmCoreGetRootDirectoryUrl() . '/apps';
        $this->CoreAppDirectoryPath = $this->sdmCoreGetCoreDirectoryPath() . '/apps';
        $this->CoreAppDirectoryUrl = $this->sdmCoreGetCoreDirectoryUrl() . '/apps';
        $this->DataDirectoryPath = $this->sdmCoreGetCoreDirectoryPath() . '/sdm';
        $this->DataDirectoryUrl = $this->sdmCoreGetCoreDirectoryUrl() . '/sdm';
        $this->requestedPage = (isset($_GET['page']) && $_GET['page'] != '' ? $_GET['page'] : 'homepage');
        $this->DataObject = (isset($this->DataObject) ? $this->DataObject : $this->sdmCoreLoadDataObject());
        $this->CurrentTheme = $this->sdmCoreDetermineCurrentTheme();
        $this->CurrentThemeDirectoryPath = $this->sdmCoreGetThemesDirectoryPath() . '/' . $this->sdmCoreDetermineCurrentTheme();
        $this->CurrentThemeDirectoryUrl = $this->sdmCoreGetThemesDirectoryUrl() . '/' . $this->sdmCoreDetermineCurrentTheme();
        /* $this->availablePages is set internally by sdmCoreLoadDataObject(); */
    }

    /**
     * <p>Returns the path to the SDM CMS root directory</p>
     * @return string <p>The path to the SDM CMS root directory as a string.</p>
     */
    final public function sdmCoreGetRootDirectoryPath()
    {
        return $this->RootDirectoryPath;
    }

    /**
     * <p>Returns the SDM CMS root url</p>
     * @return string <p>The root url for the SDM CMS as a string.</p>
     */
    final public function sdmCoreGetRootDirectoryUrl()
    {
        return $this->RootDirectoryUrl;
    }

    /**
     * <p>Returns the path to the SDM CMS core directory</p>
     * @return string <p>the path to the SDM CMS core directory as a string.</p>
     */
    final public function sdmCoreGetCoreDirectoryPath()
    {
        return $this->CoreDirectoryPath;
    }

    /**
     * <p>Returns the url to the SDM CMS core directory</p>
     * @return string <p>the url to the SDM CMS core directory as a string.</p>
     */
    final public function sdmCoreGetCoreDirectoryUrl()
    {
        return $this->CoreDirectoryUrl;
    }

    /**
     * <p>Loads the entire content object from data.json or the DB and returns it.</p>
     * @return object <p>The content object loaded from $this->CoreDirectoryUrl/sdm/data.json or from the DB</p>
     */
    final public function sdmCoreLoadDataObject($requestPageOnly = true)
    {
        // determine requested page
        $requestedPage = $this->sdmCoreDetermineRequestedPage();
        // load json string from data.json via curl
        $coreJson = $this->sdmCoreCurlGrabContent($this->sdmCoreGetDataDirectoryUrl() . '/data.json');
        // decode json to get our Data Object
        $dataObject = json_decode($coreJson);
        /* Before modifying the loaded Data Object create an array of available pages so we dont have to
        load data.json whenever we need to know the available pages */
        $this->availablePages = array_keys(get_object_vars($dataObject->content));
        // if $requestPageOnly === TRUE only load $dataObject->content->$requestedPage
        if ($requestPageOnly === true) {
            // get the requested pages page content | this will be used to restore $datObject->content after it is unset
            $requestedPageContent = (isset($dataObject->content->$requestedPage) === true ? $dataObject->content->$requestedPage : new stdClass());
            // unset $dataObject->content to remove all pags
            unset($dataObject->content);
            // init new $dataObject->content
            $dataObject->content = new stdClass();
            // add $requestedPage back into $dataObject->content
            $dataObject->content->$requestedPage = $requestedPageContent;
        }
        return $dataObject;
    }

    /**
     * @return string The requested page
     */
    final public function sdmCoreDetermineRequestedPage()
    {
        return $this->requestedPage;
    }

    /**
     * <p>Performs a simple CURL request one the given <b>$url</b></p>
     * @param string $url <p>the url we are targeting</p>
     * @param array $post <p>Array of post data to send, if array is empty
     * no post data will be sent</p>
     * @todo <p>At the moment this method will throw an error for empty files,
     * as well as bad requests, you can fix this by checking for null instead of ''
     * in your code.</p>
     * <br><p>i.e.,<br><br><?php<br>if(sdm_curl_grab_content($url) === null)
     * {<br>//do something<br>} else {<br>// do something else<br>}<br>?></p>
     * @return string <p>Returns results as a string of HTML.</p>
     */
    final public function sdmCoreCurlGrabContent($url, array $post = array())
    {
        if (strval($url) !== $url) { // if the string value of $url is not equal to $url than $url is not a string...
            throw new Exception('Bad type passed to sdm_curl_grab_content. $url must be a string              ');
        }
        // -- CURL session --
        $ch = curl_init();
        // we need to mimic a browser to get the actual web page data | otherwise some servers will "withhold" some of that data
        $useragent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36"; // mimics a browser | we use an older browser to further prevent the target site from stopping us from getting the data we want
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIESESSION, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (isset($post) && !empty($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * <p>Returns the url to the data directory</p>
     * @return string <p>The url to the data directory.</p>
     */
    final public function sdmCoreGetDataDirectoryUrl()
    {
        return $this->DataDirectoryUrl;
    }

    /**
     * <p>Attempts to determine the current theme based values stored in settings.json.
     * If a theme is not set/found then the defual core theme will be returned.</p>
     * @return string <p>The name of the Current site theme as a string</p>
     */
    final public function sdmCoreDetermineCurrentTheme()
    {
        return $this->DataObject->settings->theme;
    }

    /**
     * <p>Returns the path to the SDM CMS themes directory
     * (i.e., the directory where all themes are kept)</p>
     * @return string <p>the path to the SDM CMS themes directory as a string.</p>
     */
    final public function sdmCoreGetThemesDirectoryPath()
    {
        return $this->ThemesDirectoryPath;
    }

    /**
     * <p>Returns the url to the SDM CMS themes directory
     * (i.e., the directory where all themes are kept)</p>
     * @return string <p>the url to the SDM CMS themes directory as a string.</p>
     */
    final public function sdmCoreGetThemesDirectoryUrl()
    {
        return $this->ThemesDirectoryUrl;
    }

    /**
     * Determines what pages exist for the current site returning an indexed array of all the pages.
     * This method is used internally, and can also be used by developers to
     * do things like create a security checks, for instance insuring only pages
     * that actually exist and are part of the site are accessed.
     * @return array An associative array structured array('Page Name' => 'pageName');
     *
     */
    final public function sdmCoreDetermineAvailablePages()
    {
        return $this->availablePages;
    }

    /**
     * Returns get_object_vars() for the calling object.
     * @return array <p>An associative array of defined
     * object accessiblenon-static properties for the
     * specified object in scope. If a property has not
     * been assigned a value, it will be returned with a
     * null value.</p>
     */
    public function info()
    {
        return get_object_vars($this);
    }

    /**
     * <p>Returns the url to the current chosen themes directory</p>
     * @return string <p>The url to the directory for the sites current theme as a string.</p>
     */
    final public function sdmCoreGetCurrentThemeDirectoryUrl()
    {
        return $this->CurrentThemeDirectoryUrl;
    }

    /**
     * <p>Returns the url to the user apps directory</p>
     * @return string <p>The url to the user apps directory.</p>
     */
    final public function sdmCoreGetUserAppDirectoryUrl()
    {
        return $this->UserAppDirectoryUrl;
    }

    /**
     * <p>Returns the url to the user apps directory</p>
     * @return string <p>The url to the user apps directory.</p>
     */
    final public function sdmCoreGetCoreAppDirectoryUrl()
    {
        return $this->CoreAppDirectoryUrl;
    }

    /**
     * <p>Returns the path to the data directory</p>
     * @return string <p>The path to the data directory.</p>
     */
    final public function sdmCoreGetDataDirectoryPath()
    {
        return $this->DataDirectoryPath;
    }

    /**
     * <p>Returns the core DataObject created from the data in data.json</p>
     * @return object <p>The core data object created from the data in data.json.</p>
     */
    final public function sdmCoreGetDataObject()
    {
        return $this->DataObject;
    }

    /**
     * Configure PHP settings and Core settings.
     * @return boolen Returns true regardless of success.
     */
    final public function sdmCoreConfigureCore()
    {
        // turn on error reporting | @todo make this reflect site settings so admin can turn on or off based on wheater in dev or not...
        error_reporting(E_ALL | E_STRICT | E_NOTICE);
        /** modify our ini settings to fit the needs of our CMS */
        // ERRORS //
        ini_set('log_errors', '1'); // will force php to log all errors to the Server's log files
        ini_set('error_log', $this->sdmCoreGetCoreDirectoryPath() . '/logs/sdm_core_errors.log');
        ini_set('display_errors', 0); // this line should be commented out once out of dev
        // MISC //
        ini_set('auto_detect_line_endings', true); // enables PHP to interoperate with Macintosh systems @see "http://www.php.net/manual/en/filesystem.configuration.php#ini.auto-detect-line-endings" for more information | the slight performance penalty is worth insuring that PHP's file functions will be able to determine the end of lines on all OS's
        // SESSIONS //
        ini_set('session.use_trans_sid', 0);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.hash_function', 'sha512');
        ini_set('session.hash_bits_per_character', 6);
        ini_set('session.gc_maxlifetime', 180); // set in seconds | determines how a long a session file can exist before it becomes eligible for Garbage Collection
        ini_set('session.gc_probability', 20); // chance that GC will occur
        ini_set('session.gc_divisor', 100); // probability divisor, if gc_propbability is 50 and gc_divisor is 100 then there is a 50% chance of GC (i.e. 50/100)
        // set include path
        set_include_path($this->sdmCoreGetIncludesDirectoryPath());
        // include timezone file
        require($this->sdmCoreGetConfiguratonDirectoryPath() . '/timezone.php');
        // @depreceated : we only use objects now... | include dev functions | remove once out of dev
        //require($this->sdmCoreGetIncludesDirectoryPath() . '/dev_functions.php');
        return true;
    }

    /**
     * <p>Returns the path to the SDM CMS includes directory</p>
     * @return string <p>the path to the SDM CMS includes directory as a string.</p>
     */
    final public function sdmCoreGetIncludesDirectoryPath()
    {
        return $this->IncludesDirectoryPath;
    }

    /**
     * <p>Returns the path to the SDM CMS configuration directory</p>
     * @return string <p>the path to the SDM CMS configuration directory as a string.</p>
     */
    final public function sdmCoreGetConfiguratonDirectoryPath()
    {
        return $this->ConfigurationDirectoryPath;
    }

    /**
     * <p>Returns the path to the SDM CMS configuration directory</p>
     * @return string <p>the path to the SDM CMS configuration directory as a string.</p>
     */
    final public function sdmCoreGetConfiguratonDirectoryUrl()
    {
        return $this->ConfigurationDirectoryUrl;
    }

    /**
     * <p>Reads an array and outputs its data as html via PHP's <b><i>echo</i></b></p>.
     * @param type $array : <p>The array to read</p>
     * @param type $sub : <p>Set internally, determines if were handling a sub array of the
     * parent array</p>
     * @return bool <p>Returns true regardless of success. This function is simply used to
     * echo an array's data so if it cant read the array the array is corrupted and needs
     * to be re-structured</p>
     */
    final public function sdmCoreSdmReadArray($array, $sub = false, $parent = '')
    {
        $style = 'border:1px dashed limegreen;border-radius:3px;margin:25px;padding:12px;width:90%;overflow:auto;background:#000000;color:#ffffff;';
        echo '<div style="' . $style . '">';
        echo($sub === false ? '' : "<i style='color:#00CCFF;'>{$parent} (<i style='color:aqua;'>" . gettype($array) . "</i>) <span style='color:#00BB00;font-size:.7em;'>Element Count: " . count($array) . "</span> => </i>");
        if (is_bool($array) || is_string($array) || is_integer($array)) {
            $v = $array;
            unset($array);
            $array = (is_bool($v) ? array(gettype($v) => ($v === true ? 'true' : 'false')) : array(gettype($v) => strval($v)));
        }
        foreach ($array as $key => $value) {
            switch (is_array($value)) {
                case true:
                    self::sdmCoreSdmReadArray($value, true, $key);
                    break;
                default:
                    if (is_object($value)) {
                        echo($sub === false ? '<p><b style="color:#00CCFF;"><i>' . (isset($key) ? strval($key) : 'unknown_object') . '</i></b> (<i style="color:aqua;">object</i>)</p>' : '<p><ul><li><b style="color:#00FF99;"><i>' . (isset($key) ? strval($key) : 'unknown_object') . '</i></b>(<i style="color:aqua;">object</i>)</li></ul></p>');
                        self::sdmCoreSdmReadArray(json_decode(json_encode($value), true));
                    } else {
                        echo($sub === false ? "<p><xmp style='display:inline;color:#00CCFF'>{$key}</xmp> (<i style='color:aqua;'>" . gettype($value) . "</i>) " . (gettype($value) === 'string' ? '<span style="color:#00DDFF;font-size:.7em;font-style: italic;">String Length: ' . strlen($value) . '</span>' : '') . " => <xmp style='display:inline;color:#00CC99'>{$value}</xmp></p>" : "<p><ul><li><xmp style='display:inline;color:#00CC99'>{$key}</xmp> (<i style='color:aqua;'>" . gettype($value) . "</i>) " . (gettype($value) === 'string' ? '<span style="color:#00DDFF;font-size:.7em;font-style: italic;">String Length: ' . strlen($value) . '</span>' : '') . " => <xmp style='display:inline;color:#00CC99'>{$value}</xmp></li></ul></p>");
                    }
                    break;
            }
        }
        echo '</div>';
        return true;
    }

/////////////////////////////////
///////////// Data //////////////
/////////////////////////////////

    /**
     *  Attempts to return a directory listing for the specified directory (i.e., $directoryName)
     * @param string $directoryName <p>The name of the directory to create a listing of.</p>
     * @param string $directoryLocationReference <p>The name of a directory to be used as a starting reference point to search for the directory we want to create a listing for.
     * <br><br>
     * <i>$this->sdmCoreGetDirectoryListing('', 'core')</i>
     * <br><br>
     * would return a directory listing for '<b>SITESROOTURL</b>/core/'. (Note: passing an empty string will return the name of the directory being used as a locational reference.(i.e., $directoryLocationReference)
     * <br><br><b>(Note: there is one special value you can pass to this parameter, the 'CURRENT_THEME' value will return a directory listing for the current theme)</b>
     * </p>
     * @return array A directory listing for $directoryName as an array.
     */
    final public function sdmCoreGetDirectoryListing($directoryName, $directoryLocationReference)
    {
        switch ($directoryLocationReference) {
            // search for directory in site root
            case 'root':
                return scandir($this->sdmCoreGetRootDirectoryPath() . '/' . $directoryName);
                break;
            // search for directory in site core
            case 'core':
                return scandir($this->sdmCoreGetCoreDirectoryPath() . '/' . $directoryName);
                break;
            // search for directory in site themes
            case 'themes':
                return scandir($this->sdmCoreGetRootDirectoryPath() . '/themes/' . $directoryName);
                break;
            case 'CURRENT_THEME':
                return scandir($this->sdmCoreGetCurrentThemeDirectoryPath() . '/' . $directoryName);
                break;
            case 'apps':
                return scandir($this->sdmCoreGetUserAppDirectoryPath() . '/' . $directoryName);
                break;
            case 'coreapps':
                return scandir($this->sdmCoreGetCoreAppDirectoryPath() . '/' . $directoryName);
                break;
            case 'userapps':
                return scandir($this->sdmCoreGetUserAppDirectoryPath() . '/' . $directoryName);
                break;
            default:
                return array('error' => 'Unable to find requested directory');
                break;
        }
    }

    /**
     * <p>Returns the path to the current chosen themes directory</p>
     * @return string <p>The path to the directory for the sites current theme as a string.</p>
     */
    final public function sdmCoreGetCurrentThemeDirectoryPath()
    {
        return $this->CurrentThemeDirectoryPath;
    }

    /**
     * <p>Returns the path to the user apps directory</p>
     * @return string <p>The path to the user apps directory.</p>
     */
    final public function sdmCoreGetUserAppDirectoryPath()
    {
        return $this->UserAppDirectoryPath;
    }

    /**
     * <p>Returns the path to the core apps directory</p>
     * @return string <p>The path to the core apps directory.</p>
     */
    final public function sdmCoreGetCoreAppDirectoryPath()
    {
        return $this->CoreAppDirectoryPath;
    }

    /**
     * <p>Returns an array of available pages as stored in the availablePages property.</p>
     * <p><i>Note: This method was created so that components that needed to determine
     * that available pages did not have to call sdmCoreDetermineAvailablePages()
     * which resulted in sdmCoreLoadDataObject() being called more then necessary.
     * Instead the sdmCoreDetermineAvailablePages() is called from withing SdmCore's
     * constructor and the resulting array is stored in the availablePages property
     * upon instantiation of an SdmCore() object. Again, this change is one of many
     * changes being made to reduce the number of times data.json is loaded either
     * explicitly or via sdmCoreLoadDataObject().</i></p>
     */
    final public function sdmCoreListAvailablePages()
    {
        return $this->availablePages;
    }

    /**
     * <p>Returns object stored in DataObject->settings->enabledapps.</p>
     * @return object <p>$this->DataObject->settings->enabledapps.</p>
     */
    final public function sdmCoreDetermineEnabledApps()
    {
        return $this->DataObject->settings->enabledapps;
    }

    /**
     *
     * @param mixed $value The value to convert into a machine safe string. If an array is passed each value in the array will be filtered
     * @return mixed A machince safe string. If an array was passed then it's values will be filtered recursivley
     */
    final public function SdmCoreGenerateMachineName($value)
    {
        $targetChars = str_split('~!@#$%^&*()+|}{":;?> <`\'\\Ω≈ç√∫˜≤≥÷åß∂ƒ©˙∆˚¬…æœ∑´†¥¨ˆπ“‘«`™£¢∞§¶•ªº–≠¸˛Ç◊ı˜Â¯˘¿ÅÍÎÏ˝ÓÔÒÚÆŒ„´‰ˇÁ¨ˆØ∏”’»`⁄€‹›ﬁﬂ‡°·‚—±');
        switch (is_array($value)) {
            case true:
                foreach ($value as $k => $v) {
                    unset($value[$k]);
                    $value[$k] = SdmCore::SdmCoreGenerateMachineName($v);
                    unset($k);
                    unset($v);
                }
                break;

            default:
                $value = str_replace($targetChars, '_', $value);
                break;
        }
        // remove any dulicate underscores
        $machineValue = preg_replace('/[_]+/', '_', $value);
        return strtolower($machineValue);
    }

    /**
     * <p>Returns a substring between two strings from a string.</p>
     * <p>i.e.,</p>
     * <p>sdmCoreStrSlice('Some string to slice.', 'to','.'); // returns 'slice'</p>
     * <p>Note: <i>Niether the $start or $end strings will be included in the slice.</i></p>
     * @param string $string <p>String to get slice from.</p>
     * @param string $start <p>Starting string, i.e., the chars to start the slice after</p>
     * @param string $end <p>The ending string, i.e., the chars to end the slice at</p>
     * @return string <p>The slice of the string between $start and $end.</p>
     */
    final public function sdmCoreStrSlice($string, $start, $end)
    {
        $string = " " . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return "";
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

}
