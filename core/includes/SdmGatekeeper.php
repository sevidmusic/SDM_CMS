<?php

/**
 * The SdmGatekeeper is responsible for security and security related components.
 * It implements PHP's built in SessionHandlerInterface in order to provide
 * custom session handling for the SDM CMS.
 * In addition, the SdmGatekeeper provides other important security related
 * components including custom encryption and decryption methods, custom data
 * filters, and random data generators that can be used to create unique ids,
 * security tokens, security keys, and cipher keys.
 */
class SdmGatekeeper extends SdmCore implements SessionHandlerInterface {

    /** Properties */
    private $savePath;

    /** Custom Session Handler Methods */

    /**
     * <p>Initialize a session.</p>
     * <p>Note: The two parameters do not need to be set since PHP sets them
     * automatically.</p>
     * @param string $savePath <p>The path where to store/retrieve the session. This parameter will
     *                            not have any effect if the $storageType is not set to FILE</p>
     * @param string $sessionName <p>The session name used by php, the default is PHPSESSID.</p>
     * @param string $storageType <p>For now, this parameter is just a placeholder,
     *                               in the future this parameter will determine where the session
     *                               data is pulled from, i.e., a session file, or a Database, or
     *                               some other storage.</p>
     * @return bool <p>The return value (usually true on success, false on
     *                 false on failure).
     *                 Note: This value is returned internally to PHP for
     *                 processing.</p>
     */
    public function open($savePath, $sessionName, $storageType = 'FILE') {
        $this->savePath = $savePath;
        switch ($storageType) {
            case 'FILE':
                // Make sure our save path exists, if not create it.
                if (!is_dir($this->savePath)) {
                    mkdir($this->savePath, 0777); // @TODO: LOOK INTO WHAT PERMISSION SARE MOST SECURE FOR THIS DIR
                }
                break;
            default:
                error_log('PHP Warning: SdmGateKeeper - Invalid storage type requested for session "' . $sessionName . '" with id ending in ' . substr(session_id(), -7) . '.');
                break;
        }
        return;
    }

    public function read($sessionId) {
        // read and decrypt our session data
        $sessionData = $this->sdmNice(@file_get_contents($this->savePath . '/' . $sessionId));
        return (string) $sessionData;
    }

    public function write($sessionId, $sessionData) {
        // encrypt and write our session data
        $status = (file_put_contents($this->savePath . '/' . $sessionId, $this->sdmKind($sessionData), LOCK_EX) === false ? false : true);
        return $status;
    }

    /**
     * <p>Close a session.</p>
     * @return bool <p>The return value (usually true on success, false on
     *                 false on failure).
     *                 Note: This value is returned internally to PHP for
     *                 processing.</p>
     */
    public function close() {
        /**
         * Closing the session occurs at the end of the session life cycle,
         * just after the session data has been written. No parameters are
         * passed to this callback so if you need to process something here
         * specific to the session, you can call session_id() to obtain the ID.
         * i.e., if you need to do something here you can reference the session
         * with session_id()
         */
        return;
    }

    public function destroy($sessionId) {
        $file = $this->savePath . '/' . $sessionId;
        if (file_exists($file)) {
            unlink($file);
        }
        return true;
    }

    public function gc($maxlifetime) {
        foreach (glob("$this->savePath/*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }

    /** Session Info Methods */

    /**
     * <p>Utilizes sdmCoreSdmReadArray() to show info
     * about the current configuration of PHP sessions, unless
     * the $reutrnData var is set to true, in which case
     * the an array of data about the current session config is
     * returned.</p>
     * <p>This method is useful for debugging and development.</p>
     * @param bool $returnData <p>If set to true, an array of data
     *                            about the current session config
     *                            will be returned.</p>
     * @param bool $resizeableView <p>Determines if view is resizeable
     *                                or not. Defaults to false</p>
     * @return bool <p>Returns true.</p>
     */
    public function sessionConfigInfo($returnData = false, $resizeableView = false) {
        $sessionConfigInfo = array(
            'session_module_name' => session_module_name(),
            'session_id' => (session_id() === '' ? 'There is no session id at the moment which means there is no active session' : session_id()),
            'session_name' => session_name(),
            'session_save_path' => session_save_path(),
            'session_get_cookie_params' => session_get_cookie_params(),
            'session_cache_limiter' => session_cache_limiter(),
            '$_SESSION' => (!empty($_SESSION) === true ? $_SESSION : 'No current $_SESSION data'),
        );
        if ($returnData === false) {
            echo '<div style="' . ($resizeableView === true ? 'width:100%;height:42px;resize:both;overflow:auto;' : '') . '">';
            $this->sdmCoreSdmReadArray($sessionConfigInfo);
            echo '</div>';
            return true;
        } else {
            return $sessionConfigInfo;
        }
    }

    /**
     * Start a session. This method should be used in place of
     * PHP's session_start().
     * This method sets up our session configuration, startrs our session,
     * and provides security checks that will end a session if they discover
     * something insecure. Also applies timeout limits on our sessions.
     * @TODO: We should apply sesson id regeneration for security, however
     *        attempts to do so have resulted in session data being lost, so
     *        until session id regeneration can be implemented without bugs
     *        it is not being applied.
     * @return bool <p>true if session was started, false if session
     * could not be started.</p>
     */
    public function sessionStart() {
        $handler = $this;
        session_set_save_handler($handler, true);
        // set our session name | it is important for this to be unique to our site for security reasons
        session_name('sdmsession');
        // how long the session cookie will be valid
        $maxlifetime = ini_get('session.gc_maxlifetime');
        // path session cookie will be available on
        $path = '/';
        // domain our session cookie will be available to. | NOTE: Setting this parameter is resulting is session data being lost, until it is discovered how to set the $domain parameter without any problems set to an empty string.
        $domain = ''; //$this->SdmCoreGetRootDirectoryUrl();
        // If set to true session cookie will only be available over encrypted connections such as SSL/TLS. Setting this to true on a non-encrypted connection will result in session data loss.
        $secure = false;
        // If set to true it forces seesions to only use HTTP with browsers that support this parameter. If supported setting this to true will prevent javascript from interacting with the cookie which is useful in defending against XSS attacks.
        $httponly = true;
        session_set_cookie_params($maxlifetime, $path, $domain, $secure, $httponly);
        // start session, and store result in $status var so the sessionStart() method will return true or false depending on the success of session_start()
        $startStatus = session_start();
        // set referer token which is used to insure requests are from our site
        $_SESSION['referer_token'] = $this->sdmKind($this->sdmCoreGetRootDirectoryUrl());
        // store decoded refer_token in $_SESSION, if it is not === to the site root url then this request is not from our site
        $_SESSION['site_root_url'] = ($this->sdmNice($_SESSION['referer_token']) === $this->sdmCoreGetRootDirectoryUrl() ? $this->sdmNice($_SESSION['referer_token']) : 'invalid_referer');
        /** check if valid request | if $_SESSION['site_root_url'] has the 'invalid_referer' value or $_SESSION['site_root_url'] does NOT equal the our sites root url terminate session because request did not come from our site */
        if ($_SESSION['site_root_url'] === 'invalid_referer' || $_SESSION['site_root_url'] != $this->sdmCoreGetRootDirectoryUrl()) {
            // request did not come from our site, delete the
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
        }
        /** Make sure sessions timeout appropriatly if user is no longer active * */
        // check LAST_ACTIVITY against current time to see if last request was more then $maxlifetime seconds ago.
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $maxlifetime)) {
            // last request is older than $maxlifetime
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
        }
        // update last activity time stamp
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
        /** Regenerate session id on every request for security */
        $regenStatus = session_regenerate_id(true);
        return ($startStatus && $regenStatus === true ? true : false);
    }

    public function sessionDestroy() {
        return session_destroy();
    }

    /////////////////////////////////
    ///// Security : Encryption /////
    /////////////////////////////////

    /**
     * <p>Encrypts a string.</p>
     * @param string $data <p>The data to be kind</p>
     * @return string <p>The encrypted string</p>
     */
    final public function sdmKind($data) {
        $cipher = '>#|@zl)VR-1ZYP{8g~iJAxy^(\\?INr!*UBuMqt7nvk}&`wE6b:H03KXOm/f.c"[S]a<LGe\'p;o9,C2h%F=dDs$5jW_TQ+4';
        $limit = strlen($cipher);
        $offset = rand(0, $limit - 1);
        $kind = '';
        for ($sweet = 0; $sweet < strlen($data); $sweet++) {
            $empathy = (strpos($cipher, $data[$sweet]) === false ? 'notfound' : strpos($cipher, $data[$sweet])); // current position in unencrypted string, to be encrypted it must be less than the cipher length because the encrypted chars will only be chars that exist in cipher, therefore any chars with positions greater than the cipher length will not be encrypted
            $love = ($empathy + $offset >= $limit ? ($empathy - ($limit - $offset)) : ($empathy + $offset));
            $kind .= ( $empathy === 'notfound' ? $data[$sweet] : $cipher[$love]);
        }
        return $kind . 'sdm' . $offset;
    }

    /**
     * <p>Decrypts a string encrypted with <b>sdmKind()</b></p>
     * @param string  $data <p>The string to be decrypted.</p>
     * @return string <p>The decrypted string.</p>
     */
    final public function sdmNice($data) {
        $cipher = '>#|@zl)VR-1ZYP{8g~iJAxy^(\\?INr!*UBuMqt7nvk}&`wE6b:H03KXOm/f.c"[S]a<LGe\'p;o9,C2h%F=dDs$5jW_TQ+4';
        $limit = strlen($cipher);
        $key = explode('sdm', $data);
        $offset = end($key);
        $new = strstr($data, 'sdm', true);
        $kind = '';
        for ($sweet = 0; $sweet < strlen($new); $sweet++) {
            $empathy = (strpos($cipher, $new[$sweet]) === false ? 'notfound' : strpos($cipher, $data[$sweet])); // current position in unencrypted string, to be encrypted it must be less than the cipher length because the encrypted chars will only be chars that exist in cipher, therefore any chars with positions greater than the cipher length will not be encrypted
            $love = ($empathy + ($limit - $offset) >= $limit ? ($empathy - $offset) : ($empathy + ($limit - $offset)));
            $kind .= ( $empathy === 'notfound' ? $new[$sweet] : $cipher[$love]);
        }
        return $kind;
    }

    /**
     * Determines if user is logged in.
     * @return bool <p>true if user is logged in, false if not.</p>
     */
    final public static function sdmGatekeeperAuthenticate() {
        return (isset($_SESSION['sdmauth']) && $_SESSION['sdmauth'] === 'auth' ? true : false);
    }

    /**
     * <p>Determines user role, if user is not assigned a role
     * then the 'anonymous' role is returned.</p>
     * @return string <p>The user role</p>
     */
    final public function SdmGatekeeperDetermineUserRole() {
        return (isset($_SESSION['userRole']) ? $_SESSION['userRole'] : 'anonymous');
    }

    /**
     * <p>Reads gatekeeper parameters from an apps .gk file if one is provided.
     * If present, the .gk file is used by internally by the SDM CMS to determine
     * which 'roles' are allowed to use an app. Other parameters may be set
     * but they are not required.</p>
     * <p><i><b>Note:</b><br/>
     * If an app does provides a .gk file then the 'roles'
     * parameter must be present or else the .gk file will be ignored.<br/>
     * Developers are free to add other params to the .gk file, and the use
     * the sdmGatekeeperReadAppGkParams() method from within their app to
     * read those parameters and use them in their app.</i></p>
     * @param string $app <p>The name of the app to whose .gk file we want
     * to get parameters from.</p>
     * @return array <p>Associative array of parameters from the app's .gk
     * file or false if no .gk file is exists.
     */
    final public function sdmGatekeeperReadAppGkParams($app) {
        if (file_exists($this->sdmCoreGetCoreAppDirectoryPath() . '/' . $app . '/' . $app . '.gk')) {
            $gkfile = file($this->sdmCoreGetCoreAppDirectoryPath() . '/' . $app . '/' . $app . '.gk');
            $params = $this->sdmGatekeeperDecodeParams($gkfile);
        } else if (file_exists($this->sdmCoreGetUserAppDirectoryPath() . '/' . $app . '/' . $app . '.gk')) {
            $gkfile = file($this->sdmCoreGetUserAppDirectoryPath() . '/' . $app . '/' . $app . '.gk');
            $params = $this->sdmGatekeeperDecodeParams($gkfile);
        } else {
            $params = false;
        }
        return $params; //$params;
    }

    /**
     * Decodes parameters stored in .gk file and returns them in an associative array.
     * @param array $gkfile <p>The array returned from loading the .gk file via file().<br/>
     * i.e,<br/>
     * <i>before calling this method store .gk file by loading via file() adn storeing the resulting array in a var</i><br/>
     * <b>$var = file('PATH/TO/.gk/FILE');</b><br/>
     * <i>then pass that var to this method.</i><br/>
     * <b>SdmGatekeeper::sdmGatekeeperDecodeParams($var);</b>
     * </p>
     * @return array <p>Associative array of .gk params decoded from an array returned by loading a .gk file via file()</p>
     */
    final private function sdmGatekeeperDecodeParams($gkfile) {
        foreach ($gkfile as $value) {
            $paramName = $this->sdmCoreStrSlice('->' . $value, '->', '=');
            $paramValuesString = $this->sdmCoreStrSlice($value, '=', ';');
            $paramValuesArray[$paramName] = explode(',', $paramValuesString);
        }
        return $paramValuesArray;
    }

}

