<?php

// require our startup file
require(__DIR__ . '/core/config/startup.php');

// assemble the HTML header
echo $sdmassembler->assembleHtmlHeader();

// load our theme and build the page
include($sdmcore->getCurrentThemeDirectoryPath() . '/page.php');

// assemlbe the required closing html tags/data
echo $sdmassembler->assembleHtmlRequiredClosingTags();

