<?php

// add a dev menu to all pages that includes links to all pages and enabled apps
$pages = $sdmcore->sdmCoreDetermineAvailablePages();
// Possibly incorporate the following few lines that put together a list of available apps and pages into one array into the sdmCoreDetermineAvailablePages() method
$enabledApps = json_decode(json_encode($sdmcore->sdmCmsDetermineEnabledApps()), TRUE);
$availablePages = array_merge($pages, $enabledApps);
$devMenu = '<!-- Dev Menu " Generated by core app SDM DEV TOOLS --><div style="color:aqua;background:#000000;padding:20px;border:1px solid black;border-radius:20px;"><h3>Dev Menu</h3><ul>';
foreach ($availablePages as $link) {
    $devMenu .= '<li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=' . $link . '">' . $link . '</a></li>';
}
$devMenu .= '</ul></div><!-- End Dev Menu -->';
// incorporate devmenu
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $devMenu, array('wrapper' => 'topmenu', 'incmethod' => 'prepend', 'incpages' => $availablePages));