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

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, array('incmethod' => 'prepend'));

unset($output);
$output = '
    <h3>Git Tools</h3>
        <p>This app provides tools for working with git. When enalbed the current branch will appear at the top of
        every page\'s main_content wrapper. In addition, the gitTools page displays a variety of information related
        to your current working branch.</p>
        <h3 style="color:red;">Warning!</h3>
        <p style="color:red;">This app should never be enabled on a live site, it uses PHP\'s exec() function which is
        very dangerous on a live server as it can potentially give a malicious user the power to execute
        commands on the server your site is hosted on. This app was created for local use by developers, and should
        only be enabled when working on a local, non-production, site.</p>
        ';

/* Execute some git commands to create an overview of the current branch */
$gitOutput = array();

/* Git Status */
$gitStatus = exec('git status', $gitStatusOutput);
$gitInfo = '<h4>Git Status:</h4><div style="border: 5px double #ffffff; padding: 20px;">' . implode('<br>', $gitStatusOutput) . '</div>';
/* Git Log */
$gitLog = exec('git log master..' . $branchname . ' -stat --no-merges', $gitLogOutput);
$gitInfo .= '<h4>Git Log:</h4><div style="border: 5px double #ffffff; padding: 20px;">' . implode('<br>', $gitLogOutput) . '</div>';

$output .= '
    <h3>Overview of Current Branch "' . $branchname . '":</h3>
    <div style="border: 5px double #ffffff; padding: 20px;">' . $gitInfo . '</div>
    ';
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, array('incpages' => array('gitTools'), 'incmethod' => 'append'));
