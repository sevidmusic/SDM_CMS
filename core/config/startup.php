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
// start core session
$sdmGatekeeper->sessionStart();
// initialize assembler
$sdmassembler = SdmAssembler::sdmAssemblerInitializeAssembler();
// load and assemble content | this var is used excluisively by the current themes page.php
$sdmassembler_themeContentObject = $sdmassembler->sdmAssemblerLoadAndAssembleContentObject();
