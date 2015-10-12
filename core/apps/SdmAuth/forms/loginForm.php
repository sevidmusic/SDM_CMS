<?php

// check encrypted LAST_ACTIVITY against $_SESSION['auth_cleared'], if they don't match then user is NOT logged in
if (isset($_SESSION['sdmauth'])) {//isset($_SESSION['LAST_ACTIVITY']) && isset($_SESSION['auth_cleared']) && strval($_SESSION['LAST_ACTIVITY']) === $sdmAuthGk->sdmNice($_SESSION['auth_cleared'])) {
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<!-- SdmAuth Login Form -->' . '<p>Your are currently logged in.</p><p>' . '<span style="font-size:.6em;"><a href="' . $sdmassembler->SdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmAuthLogin&logout=' . session_id() . '">Logout</a></span></p>' . '<!-- End SdmAuth Login Form -->', $options);
} else {
    /** user not logged in, destroy session to insure user did not somehow get in.
     *  i.e., if LAST_ACTIVITY and $sdmAuthGk->sdmNice($_SESSION['auth_cleared'])
     * dont match but auth_cleared is set then user may be attempting to bypass
     * the normal login process to gain invalid accsess to our site...,
     * If auth_cleared is set at all then a login attempt was made, so we check that
     * the decrypted auth_cleared matches the time of the sessions creation, unless
     * the malicious user can generate a properly encrypted session creation time
     * they won't be allowed in because decryption will NOT result in a match
     * between LAST_ACTIVITY and $sdmAuthGk->sdmNice($_SESSION['auth_cleared']).
     *
      session_unset();     // unset all $_SESSION variables
      session_destroy();   // destroy all session data in storage */
    // Build and display the Login form.
    $loginForm = new SdmForm();
    $loginForm->form_handler = 'SdmAuthLogin';
    $loginForm->method = 'post';
    $loginForm->submitLabel = 'Login';
    $loginForm->form_elements = array(
        array(
            'id' => 'hidden_form_element',
            'type' => 'hidden',
            'element' => 'Hidden',
            'value' => 'defualt hidden value',
            'place' => '4',
        ),
        array(
            'id' => 'username',
            'type' => 'text',
            'element' => 'Username',
            'value' => '',
            'place' => '0',
        ),
        array(
            'id' => 'password',
            'type' => 'text',
            'element' => 'Password',
            'value' => '',
            'place' => '1',
        ),
    );
    $loginForm->sdmFormBuildForm($sdmassembler->SdmCoreGetRootDirectoryUrl());
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<!-- SdmAuth Login Form -->' . $loginForm->sdmFormGetForm() . '<!-- End SdmAuth Login Form -->', $options);
}
