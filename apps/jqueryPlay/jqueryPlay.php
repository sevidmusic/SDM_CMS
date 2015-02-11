<?php

switch ($sdmcore->determineRequestedPage()) {
    case 'jqueryPlay':
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '<!-- jqueryplay user app appended content -->';
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '<div id="jqueryplay" class="jqueryplay">';
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '<!-- jquery code for jqueryPlay app -->';
        // Add our jQuery code to the page
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '<script type="text/javascript">' . trim(file_get_contents($sdmcore->getUserAppDirectoryPath() . '/jqueryPlay/jqueryPlay.js')) . '</script>';
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '</div><!-- end jqueryplay -->';
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '<!-- end jqueryplay user app appended content -->';
        break;

    default:
        // do nothing | we dont want this apps output appearing on pages other than the jqueryPlay page | if the jqueryPlay page does not exist you can create it with from the UI in the content manager app
        break;
}