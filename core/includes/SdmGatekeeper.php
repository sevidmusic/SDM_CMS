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
class SdmGatekeeper implements SessionHandlerInterface {

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

}

