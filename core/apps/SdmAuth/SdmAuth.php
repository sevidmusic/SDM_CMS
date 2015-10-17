<?php

$options = array(
    'incpages' => array('SdmAuth', 'SdmAuthLogin'),
);

switch ($sdmassembler->SdmCoreDetermineRequestedPage()) {
    case 'SdmAuth':
        require_once($sdmassembler->SdmCoreGetCoreAppDirectoryPath() . '/SdmAuth/forms/loginForm.php');
        break;
    case 'SdmAuthLogin':
        require_once($sdmassembler->SdmCoreGetCoreAppDirectoryPath() . '/SdmAuth/handlers/SdmAuthLogin.php');
        break;

    default:
        $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<!-- SdmAuth Login Form -->' . 'The login form could not be loaded at this time. Please try again later.<br />-- Page Req : ' . $sdmassembler->SdmCoreDetermineRequestedPage() . '<!-- End SdmAuth Login Form -->', $options);
        break;
}