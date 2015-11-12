<?php

/**
 * @filename: currentgitbranch.php
 * @usage: Include this file after the '<body>' tag in your project
 * @author Kevin Ridgway
 */
$stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);

$firstLine = $stringfromfile[0]; //get the string from the array

$explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string

$branchname = $explodedstring[2]; //get the one that is always the branch name

$output = "<div style='clear:both;color: #66CCCC; background: #000000; border-radius: 9px; padding: 10px; margin: 0px 0px 20px 0px; text-align: center;'>Current GIT branch: <i style='color: #00CC33; text-transform: uppercase;'>" . $branchname . "</i></div>";

$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmAssemblerDataObject, $output, array('incmethod' => 'prepend'));

$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmAssemblerDataObject, '<h3>Git Tools</h3><p>This app provides tools for working with git. At the moment the only tool is a <i>branch viewer</i> that show the current git branch at the top of the main_content menu wrapper on every page.</p><p>Further development of this app will occur in the future, and this app will have a variety of tools for using git with your SDM CMS site.</p>', array('incpages' => array('gitTools'), 'incmethod' => 'append'));
