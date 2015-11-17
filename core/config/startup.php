<?php

/** load site CONSTANTS as defined in config.php */
require_once('config.php');

/** load classes | special PHP function, do not include in SDM CORE */
function __autoload($classes) {
    $filename = $classes . '.php';
    include_once(__SDM_INCTDIR__ . '/' . $filename);
}

// create/configure core
$sdmassembler = new SdmAssembler;
// configure core
$sdmassembler->sdmCoreConfigureCore();
// start core session
$sdmassembler->sessionStart();
// load and assemble content | this var is used excluisively by the current themes page.php
$sdmassembler->sdmAssemblerLoadAndAssembleContentObject();
