<?php

$handler = new SdmForm();
$sdmAuthGk = new SdmGatekeeper();
$devUser = 'sevid';
$devPass = 'music';
$devUserRole = 'root';
// determine if we are logging out, logging in, or displaying the form after invalid login attempt
switch (isset($_GET['logout'])) {
    case 'logout':
        session_unset();
        session_destroy();
        $sdmassembler->sdmAssemblerIncorporateAppOutput('<p>Your have been logged out successfully.</p>', $options);
        break;
    default:
        // check if login credentials were valid | if they were login user, if not then display login form and a message indicating the login credentials were not valid.
        if ($handler->sdmFormGetSubmittedFormValue('username') === $devUser && $handler->sdmFormGetSubmittedFormValue('password') === $devPass) {
            $_SESSION['sdmauth'] = 'auth';
            $_SESSION['userRole'] = $devUserRole;
            $sdmassembler->sdmAssemblerIncorporateAppOutput('<p>Your are logged in.</p>', $options);
        } else {
            $sdmassembler->sdmAssemblerIncorporateAppOutput('<p>Invalid Login Credentials</p>', $options);
            require_once($sdmassembler->SdmCoreGetCoreAppDirectoryPath() . '/SdmAuth/forms/loginForm.php');
        }
        break;
}