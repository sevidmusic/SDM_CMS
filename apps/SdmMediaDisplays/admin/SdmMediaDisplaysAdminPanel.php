<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:00 PM
 */

/* Load Sdm Media Displays admin vars. */
require($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/includes/sdmMediaDisplaysAdminVars.php');

/* Load Sdm Media Displays initial setup script. */
require($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/includes/sdmMediaDisplayInitialSetup.php');

/* Load Sdm Media Displays admin cron script. */
require($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/includes/sdmMediaDisplaysAdminCron.php');

/* Load Sdm Media Displays admin cron script. */
require($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/includes/sdmMediaDisplaysAdminPanelAssembler.php');


