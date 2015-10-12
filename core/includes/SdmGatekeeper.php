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
     * @return bool <p>The return value (usually TRUE on success, FALSE on
     *                 FALSE on failure).
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
                error_log('PHP Warning: SdmGateKeeper - Invalid storage type requested for session with id ending in ' . substr(session_id(), -7) . '.');
                break;
        }
        return;
    }

    public function read($sessionId) {
        return (string) @file_get_contents($this->savePath . '/' . $sessionId);
    }

    public function write($sessionId, $sessionData) {
        // write our session data
        $status = (file_put_contents($this->savePath . '/' . $sessionId, $sessionData, LOCK_EX) === false ? false : true);
        return $status;
    }

    /**
     * <p>Close a session.</p>
     * @return bool <p>The return value (usually TRUE on success, FALSE on
     *                 FALSE on failure).
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
     * the $reutrnData var is set to TRUE, in which case
     * the an array of data about the current session config is
     * returned.</p>
     * <p>This method is useful for debugging and development.</p>
     * @param bool $returnData <p>If set to TRUE, an array of data
     *                            about the current session config
     *                            will be returned.</p>
     * @param bool $resizeableView <p>Determines if view is resizeable
     *                                or not. Defaults to FALSE</p>
     * @return bool <p>Returns TRUE.</p>
     */
    public function sessionConfigInfo($returnData = FALSE, $resizeableView = FALSE) {
        $sessionConfigInfo = array(
            'session_module_name' => session_module_name(),
            'session_id' => (session_id() === '' ? 'There is no session id at the moment which means there is no active session' : session_id()),
            'session_name' => session_name(),
            'session_save_path' => session_save_path(),
            'session_get_cookie_params' => session_get_cookie_params(),
            'session_cache_limiter' => session_cache_limiter(),
            '$_SESSION' => (!empty($_SESSION) === TRUE ? $_SESSION : 'No current $_SESSION data'),
        );
        if ($returnData === FALSE) {
            echo '<div style="' . ($resizeableView === TRUE ? 'width:100%;height:42px;resize:both;overflow:auto;' : '') . '">';
            $this->sdmCoreSdmReadArray($sessionConfigInfo);
            echo '</div>';
            return TRUE;
        } else {
            return $sessionConfigInfo;
        }
    }

    /**
     * Start a session. This method should be used in place of
     * PHP's session_start().
     * This method sets up our session configuration, startrs our session,
     * and provides security checks abd applies timeout limits on our sessions.
     * @return bool <p>TRUE if session was started, false if session
     * could not be started.</p>
     */
    public function sessionStart() {
        $handler = $this;
        session_set_save_handler($handler, true);
        // set our session name | it is important for this to be unique to our site for security reasons
        session_name('sdmsession');
        session_start();
        // set referer token which is used to insure requests are from our site
        $_SESSION['referer_token'] = $this->sdmKind($this->sdmCoreGetRootDirectoryUrl());
        // store decoded refer_token in $_SESSION, if it is not === to the site root url then this request is not from our site
        $_SESSION['site_root_url'] = ($this->sdmNice($_SESSION['referer_token']) === $this->sdmCoreGetRootDirectoryUrl() ? $this->sdmNice($_SESSION['referer_token']) : 'invalid_referer');
        /** Make sure sessions timeout appropriatly if user is no longer active * */
        $maxlifetime = ini_get('session.gc_maxlifetime');
        // check LAST_ACTIVITY against current time to see if last request was more then $maxlifetime seconds ago.
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $maxlifetime)) {
            // last request is older than $maxlifetime
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
        }
        // update last activity time stamp
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
        return; //$status;
        /*
         * During development it became clear through some research
         * that PHP's handling of session timeout is not reliable.
         * The code below addresses this.
         * @see http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
         * @see http://php.net/manual/en/function.session-set-cookie-params.php
         *
          // set session cookie params manually
          $maxlifetime = ini_get('session.gc_maxlifetime');
          $secure = TRUE;
          $httponly = TRUE;
          session_set_cookie_params($maxlifetime, session_save_path(), $this->SdmCoreGetRootDirectoryUrl(), $secure, $httponly);
          // re-set the session cookie to insure the correct parameters are used
          setcookie(session_name(), session_id(), time() + $maxlifetime);
          /** Regenerate session id on every request for security *
          $status = session_regenerate_id();
         */
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
            $empathy = (strpos($cipher, $data[$sweet]) === FALSE ? 'notfound' : strpos($cipher, $data[$sweet])); // current position in unencrypted string, to be encrypted it must be less than the cipher length because the encrypted chars will only be chars that exist in cipher, therefore any chars with positions greater than the cipher length will not be encrypted
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
        $new = strstr($data, 'sdm', TRUE);
        $kind = '';
        for ($sweet = 0; $sweet < strlen($new); $sweet++) {
            $empathy = (strpos($cipher, $new[$sweet]) === FALSE ? 'notfound' : strpos($cipher, $data[$sweet])); // current position in unencrypted string, to be encrypted it must be less than the cipher length because the encrypted chars will only be chars that exist in cipher, therefore any chars with positions greater than the cipher length will not be encrypted
            $love = ($empathy + ($limit - $offset) >= $limit ? ($empathy - $offset) : ($empathy + ($limit - $offset)));
            $kind .= ( $empathy === 'notfound' ? $new[$sweet] : $cipher[$love]);
        }
        return $kind;
    }

    /**
     * Determines if user is logged in.
     * @return bool <p>TRUE if user is logged in, FALSE if not.</p>
     */
    final public static function sdmGatekeeperAuthenticate() {
        return ($_SESSION['sdmauth'] === 'auth' ? TRUE : FALSE);
    }

}

