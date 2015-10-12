<?php

$handler = new SdmForm();
$sdmAuthGk = new SdmGatekeeper();
$devUser = 'sevid';
$devPass = 'music';
switch (isset($_GET['logout'])) {
    case 'logout':
        session_unset();
        session_destroy();
        $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<!-- SdmAuth Login Form -->' . '<p>Your have been logged out successfully.</p><p>Terminated session with id : ' . $_GET['logout'] . '</p><!--End SdmAuth Login Form-->', $options);
        break;
    default:
        if ($handler->sdmFormGetSubmittedFormValue('username') === $devUser && $handler->sdmFormGetSubmittedFormValue('password') === $devPass) {
            $_SESSION['sdmauth'] = 'auth';
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<!-- SdmAuth Login Form -->' . 'Your are logged in.<!--End SdmAuth Login Form-->', $options);
        } else {
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<!-- SdmAuth Login Form -->' . 'Invalid Login Credentials<!--End SdmAuth Login Form-->', $options);
        }
        break;
}