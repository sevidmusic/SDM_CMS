<?php

/**
 * This app showss the minimum amount of code needed for an app.
 */
// Example of how to prepend app output to a content wrapper | This will show up at the begining of the content wrapper
$sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content = '<h1>Prepended Hello World</h1>' . $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content;

// Example of how to append app output to a content wrapper | This will show up at the end of the content wrapper
$sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '<h1>Appended Hello World</h1><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManager">Content Manager</a></p><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=errors">Error Log</a></p><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=core">Core</a></p>';

// read core to page
switch ($sdmcore->determineRequestedPage()) {
    case 'core': // dispaly current core configuration
        $sdmcore->sdm_read_array($sdmcore->sdmCoreLoadDataObject());
        break;
    case 'errors': // display recent errors
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= trim('<h1>Site Errors</h1>' . str_replace('[', '<p style="overflow:auto; border: 2px solid black;border-radius: 3px;background: black;color: #' . rand(11, 99) . rand(11, 99) . 'FF; margin: 3px 3px 3px 3px; padding: 3px 3px 3px 3px;">', str_replace('<br />', '</p>', nl2br(file_get_contents($sdmcore->getCoreDirectoryPath() . '/logs/sdm_core_errors.log')))));
        break;
}