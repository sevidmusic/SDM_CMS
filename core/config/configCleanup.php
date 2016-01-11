<?php

/** Delete any old session data. */
$targets = scandir(session_save_path());
foreach ($targets as $sessfile) {
    if ($sessfile != '.' && $sessfile != '..') {
        unlink(session_save_path() . '/' . $sessfile);
    }
}

/* Reset core error log. */
file_put_contents($sdmcore->sdmCoreGetCoreDirectoryPath() . '/logs/sdm_core_errors.log', '', LOCK_EX);

/* Reset core bad request log. */
file_put_contents($sdmcore->sdmCoreGetCoreDirectoryPath() . '/logs/badRequestsLog.log', '', LOCK_EX);
