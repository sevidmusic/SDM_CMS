<?php

echo '<div style="background:#DDDDDD;width:75%;border:2px solid #CCCCCC;border-radius:7px;margin:0 auto;padding:20px;">';
echo '<h1>SDM CMS</h1>';
require(__DIR__ . '/core/config/startup.php');

// reset error log
file_put_contents($sdmcore->getCoreDirectoryPath() . '/logs/sdm_core_errors.log', '', LOCK_EX);
echo 'The error log has been cleared. You can view the error log <a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=errors">HERE</a>';
echo '</div>';