<?php

switch ($sdmcore->determineRequestedPage()) {
    case 'dragNDropGatekeeper':
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<!-- dragNDropGatekeeper user app appended content -->';
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<div id="dragNDropGatekeeper" class="dragNDropGatekeeper">';
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<!-- jquery code for jqueryPlay app -->';
        // Add our jQuery code to the page
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<script type="text/javascript">' . '/* siteRootUrl set in dragNDropGatekeeper.php so app can use sdm core to determine correct url paths */var siteRootUrl = "' . $sdmcore->getUserAppDirectoryUrl() . '/dragNDropGatekeeper' . '";' . trim(file_get_contents($sdmcore->getUserAppDirectoryPath() . '/dragNDropGatekeeper/js/dragNDropGatekeeper-0.0.0/dragNDropGatekeeper.0.0.0.js')) . '</script>';
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '</div><!-- end dragNDropGatekeeper -->';
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<!-- end dragNDropGatekeeper user app appended content -->';
        break;

    default:
        // do nothing | we dont want this apps output appearing on pages other than the jqueryPlay page | if the jqueryPlay page does not exist you can create it with from the UI in the content manager app
        break;
}