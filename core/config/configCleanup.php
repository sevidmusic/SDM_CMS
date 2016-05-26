<?php

/**
 * This file is used by reset.php to cleanup old sessions and site logs.
 */

/** Delete any old session data. */
$targets = scandir(session_save_path());
foreach ($targets as $sessfile) {
    if ($sessfile != '.' && $sessfile != '..') {
        unlink(session_save_path() . '/' . $sessfile);
    }
}

/* Reset core error log. */
file_put_contents($sdmGatekeeper->sdmCoreGetCoreDirectoryPath() . '/logs/sdm_core_errors.log', '', LOCK_EX);

/* Reset core bad request log. */
file_put_contents($sdmGatekeeper->sdmCoreGetCoreDirectoryPath() . '/logs/badRequestsLog.log', '', LOCK_EX);

/* Reset sdm assembler log. */
file_put_contents($sdmGatekeeper->sdmCoreGetCoreDirectoryPath() . '/logs/sdmAssemblerLog.html', '', LOCK_EX);
