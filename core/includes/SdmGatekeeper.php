<?php

function colorText($text, $color) {
    return '<span style="color:' . $color . ';">' . $text . '</span>';
}

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

    /** Custom Session Handler Methods */
    // NOTE: Theses methods overwrite the default session handlers provided by PHP //
    public function read($session_id) {
        error_log(colorText('SdmGatekeeper->read(' . colorText('"', '#ffffff') . colorText(strval($session_id), 'purple') . colorText('"', '#ffffff') . ') called.', '#00CCFF'));
        return;
    }

    public function write($session_id, $session_data) {
        error_log(colorText('SdmGatekeeper->write(' . colorText('"', '#ffffff') . colorText(strval($session_id), 'purple') . colorText('"', '#ffffff') . ', ' . colorText('"', '#ffffff') . colorText(strval($session_data), 'purple') . colorText('"', '#ffffff') . ') called.', '#00CCFF'));
        return;
    }

    public function open($save_path, $name) {
        error_log(colorText('SdmGatekeeper->open(' . colorText('"', '#ffffff') . colorText(strval($save_path), 'purple') . colorText('"', '#ffffff') . ', ' . colorText('"', '#ffffff') . colorText(strval($name), 'purple') . colorText('"', '#ffffff') . ') called.', '#00CCFF'));
        return;
    }

    public function close() {
        error_log(colorText('SdmGatekeeper->close() called.', '#00CCFF'));
        return;
    }

    public function destroy($session_id) {
        error_log(colorText('SdmGatekeeper->destroy(' . colorText('"', '#ffffff') . colorText(strval($session_id), 'purple') . colorText('"', '#ffffff') . ') called.', '#00CCFF'));
        return;
    }

    public function gc($maxlifetime) {
        error_log(colorText('SdmGatekeeper->gc(' . colorText('"', '#ffffff') . colorText(strval($maxlifetime, 'purple')) . colorText('"', '#ffffff') . ') called.', '#00CCFF'));
        return;
    }

    /** Session Info Methods */

    /**
     * Utilizes sdmCoreSdmReadArray() to show info
     * about the current configuration of PHP sessions.
     * This method is useful for debugging and development.
     * @return boolean Returns TRUE.
     */
    public function sessionConfigInfo() {
        $sessionConfigInfo = array(
            'session_module_name' => session_module_name(),
            'session_id' => (session_id() === '' ? 'There is no session id at the moment which means there is no active session' : session_id()),
            'session_name' => session_name(),
            'session_save_path' => session_save_path(),
            'session_get_cookie_params' => session_get_cookie_params(),
            'session_cache_limiter' => session_cache_limiter(),
            '$_SESSION' => (!empty($_SESSION) === TRUE ? $_SESSION : 'No current $_SESSION data'),
        );
        $this->sdmCoreSdmReadArray($sessionConfigInfo);
        return TRUE;
    }

    /**
     * Start a session. This method should be used in place of
     * PHP's session_start().
     * @return bool <p>TRUE if session was started, false if session
     * could not be started.</p>
     */
    public function sessionStart() {
        $handler = $this;
        $status = session_set_save_handler($handler, true);
        $status = session_start();
        // regenerate session id for security
        $status = session_regenerate_id();
        return $status;
    }

    public function sessionDestroy() {
        $status = session_destroy();
        return $status;
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

}

