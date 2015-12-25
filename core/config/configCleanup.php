<?php
/** Delete any old session data and cleanup any old error logs */
$targets = scandir(session_save_path());
foreach ($targets as $sessfile) {
    if ($sessfile != '.' && $sessfile != '..') {
        unlink(session_save_path() . '/' . $sessfile);
    }
}
// reset error logs
file_put_contents($sdmcore->sdmCoreGetCoreDirectoryPath() . '/logs/sdm_core_errors.log', '', LOCK_EX);
file_put_contents($sdmcore->sdmCoreGetCoreDirectoryPath() . '/logs/badRequestsLog.log', '', LOCK_EX);
