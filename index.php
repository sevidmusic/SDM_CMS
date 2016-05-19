<?php

/**
 * Index file for the Sdm Cms. This file serves all site pages.
 */

/* Run clearstatcache() to clear PHP's file cache. The SDM CMS relies on json as it's storage
 * mechanism, which means json files are regularly being read and modified. So, to insure the
 * all information about the file is completely up to date PHP's file cache must be cleared so
 * cached values are never used when handling files.
 *
 * This code was added as a first attempt to fix to a very strange bug that causes reset.php to run randomly.
 * This is very bad of course because it means the whole site was reset, complete data loss!!! So the file cache
 * is cleared so cashed values don't somehow cause reset.php to run.
 *
 * On idea about what specifically causes reset.php to run is:
 *
 * index.php checks that data.json exists, if it doesn't it redirects to reset.php to configure
 * a new Sdm Cms site. The bug may happen if as a result of the file cache, data.json is perceived
 * to be inaccessible causing reset.php to run. Testing still needs to be done to confirm this,
 * but from the logs, research on php.net, and seeing the bug occur a few times this the current
 * diagnosis.
 *
 * @see http://php.net/manual/en/function.clearstatcache.php for more info.
 *
 */

clearstatcache(true);

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
