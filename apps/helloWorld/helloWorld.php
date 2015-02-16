<?php

/**
 * This app showss the minimum amount of code needed for an app.
 */
// Example of how to prepend app output to a content wrapper | This will show up at the begining of the content wrapper
$sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content = '<h1>Prepended Hello World</h1>' . $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content;

// Example of how to append app output to a content wrapper | This will show up at the end of the content wrapper
$sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '<h1>Appended Hello World</h1><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManager">Content Manager</a></p><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=errors">Error Log</a></p><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=core">Core</a></p>';