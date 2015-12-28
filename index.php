<?php

// first check that the site has been configured, if it hasn't redirect to reset.php | we can do this by checking if data.json exists
if(!file_exists(__DIR__ . '/core/sdm/data.json')) {
    $rootUrl = str_replace(array('/index.php', '/reset.php'), '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
    header('Location:' . $rootUrl . '/reset.php');
    unset($rootUrl);
    die();
}

// require our startup file
require(__DIR__ . '/core/config/startup.php');

// assemble the HTML header
echo $sdmassembler->sdmAssemblerAssembleHtmlHeader();

// load our theme and build the page
include($sdmassembler->sdmCoreGetCurrentThemeDirectoryPath() . '/page.php');

// assemlbe the required closing html tags/data
echo $sdmassembler->sdmAssemblerAssembleHtmlRequiredClosingTags();

