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

    /** Custom Session Handler Methods */
    // NOTE: Theses methods overwrite the default session handlers provided by PHP //
    public function read($session_id) {
        return;
    }

    public function write($session_id, $session_data) {
        return;
    }

    public function open($save_path, $name) {
        return;
    }

    public function close() {
        return;
    }

    public function destroy($session_id) {
        return;
    }

    public function gc($maxlifetime) {
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

}

