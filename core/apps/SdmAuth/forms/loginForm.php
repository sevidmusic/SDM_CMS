<?php

$loginGateKeeper = new SdmGatekeeper();
// check encrypted LAST_ACTIVITY against $_SESSION['auth_cleared'], if they don't match then user is NOT logged in
if (SdmGatekeeper::sdmGatekeeperAuthenticate() === true) {
    $sdmassembler->sdmAssemblerIncorporateAppOutput('<!-- SdmAuth Login Form -->' . '<p>Your are currently logged in.</p><p>' . '<span style="font-size:.6em;"><a href="' . $sdmassembler->SdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmAuthLogin&logout=' . session_id() . '">Logout</a></span></p>' . '<!-- End SdmAuth Login Form -->', $options);
} else {
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
            'type' => 'password',
            'element' => 'Password',
            'value' => '',
            'place' => '1',
        ),
    );
    $loginForm->sdmFormBuildForm($sdmassembler->SdmCoreGetRootDirectoryUrl());
    $sdmassembler->sdmAssemblerIncorporateAppOutput('<!-- SdmAuth Login Form -->' . $loginForm->sdmFormGetForm() . '<!-- End SdmAuth Login Form -->', $options);
}
