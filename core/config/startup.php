<?php

/** load site CONSTANTS as defined in config.php */
require_once('config.php');

/** load classes | special PHP function, do not include in SDM CORE */
function __autoload($classes) {
    $filename = $classes . '.php';
    include_once(__SDM_INCTDIR__ . '/' . $filename);
}

// create/configure core
$sdmcore = new SdmCore;
// configure core
$sdmcore->sdmCoreConfigureCore();
// startup the gatekeeper
$sdmGatekeeper = new SdmGatekeeper();
// start session
$sdmGatekeeper->sessionStart();
// set referer token which is used to insure requests are from our site
$_SESSION['referer_token'] = $sdmGatekeeper->sdmKind($sdmcore->sdmCoreGetRootDirectoryUrl());
// store decoded refer_token in $_SESSION, if it is not === to the site root url then this request is not from our site
$_SESSION['site_root_url'] = ($sdmGatekeeper->sdmNice($_SESSION['referer_token']) === $sdmcore->sdmCoreGetRootDirectoryUrl() ? $sdmGatekeeper->sdmNice($_SESSION['referer_token']) : 'invalid_referer');
// initialize assembler
$sdmassembler = SdmAssembler::sdmAssemblerInitializeAssembler();
// load and assemble content | this var is used excluisively by the current themes page.php
$sdmassembler_themeContentObject = $sdmassembler->sdmAssemblerLoadAndAssembleContentObject();
