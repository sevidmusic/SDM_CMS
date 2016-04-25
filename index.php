<?php

/**
 * Index file for the Sdm Cms. This file serves all site pages.
 */

/* If core site components have been not been configured, redirect to reset.php. */
if (!file_exists(__DIR__ . '/core/sdm/data.json')) {
    $rootUrl = str_replace(array('/index.php', '/reset.php'), '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
    header('Location:' . $rootUrl . '/reset.php');
    unset($rootUrl);
    die();
}

/* Require startup file. */
require_once(__DIR__ . '/core/config/startup.php');

/* Assemble the HTML header. */
echo $sdmassembler->sdmAssemblerAssembleHtmlHeader();

/* Load theme. */
$sdmassembler->sdmAssemblerLoadTheme();

/* Assemble the required closing html tags. */
echo $sdmassembler->sdmAssemblerAssembleHtmlRequiredClosingTags();
