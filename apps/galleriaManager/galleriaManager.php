<?php

switch ($sdmcore->determineRequestedPage()) {
    case 'galleria':
        // determine image folders in order to create links to image catagories | image folder will be considiered a catagory | i.e., if your folder structure is imgs/catagory1, imgs/catagory2 then 2 links will be created: "catagory1" and "catagory2"
        $catagories = $sdmcore->sdmCoreGetDirectoryListing('/galleriaManager/imgs', 'apps');
        // create string of image links to different image catagories and wrap in a div with id galleria-catagory-links, also set ul and li list styles so links display horizontally without bullets
        $links = '<!-- galleria image catagory links --><div id="galleria-catagory-links"><ul style="list-style-type:none;">';
        // create an iterator to check against so the "|" is written only if we are NOT on the last link
        $i = 0;
        // count $catagories and subtract the number of ignored catagories to provide an accureate catagory count
        $num_catagories = count($catagories) - 4;
        foreach ($catagories as $link) {
            $ignored_catagories = array('.DS_Store', '..', '.', 'default');
            if (!in_array($link, $ignored_catagories)) {
                $i++; // increase our iterator to indicate track how many catagories we have iterated through
                $links .= '<li style="display:inline;"><a href="' . $sdmcore->getRootDirectoryUrl() . '?page=galleria&image_set=' . $link . '">' . ucwords($link) . '</a></li>' . ($i < $num_catagories ? ' | ' : '');
            }
        }
        $links .= '</ul></div><!-- End galleria image catagory links -->';
        if (isset($_GET['image_set'])) {
            // determine what pics are in the imgs folder
            $images = $sdmcore->sdmCoreGetDirectoryListing('/galleriaManager/imgs/' . $_GET['image_set'], 'apps');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<!-- galleria core app appended content -->');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $links); // links are place outside galleria main wrapper so they do nto get overwritten by galleria.js | also allows stylesheets to effect this div without altering the galleria div
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<div id="galleria_main_wrapper">');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<script src="' . $sdmcore->getUserAppDirectoryUrl() . '/galleriaManager/galleria/galleria-1.4.2.min.js"></script>');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<div id="galleria" class="galleria" style="height:650px;">');
            $ignore = array('.', '..', '.DS_Store');
            foreach ($images as $value) {
                if (!in_array($value, $ignore)) {
                    $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<img src="' . $sdmcore->getUserAppDirectoryUrl() . '/galleriaManager/imgs/' . $_GET['image_set'] . '/' . $value . '">');
                }
            }

            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '</div><!-- end galleria -->');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '
        <script>
            Galleria.loadTheme(\'' . $sdmcore->getUserAppDirectoryUrl() . '/galleriaManager/galleria/themes/classic/galleria.classic.min.js\');
            Galleria.run(\'.galleria\');
        </script>
        ');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '</div><!-- end galleria_main_wrapper -->');
        } else {
            // determine what pics are in the imgs folder
            $images = $sdmcore->sdmCoreGetDirectoryListing('/galleriaManager/imgs/default', 'apps');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<!-- galleria core app appended content -->');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $links); // links are place outside galleria main wrapper so they do nto get overwritten by galleria.js | also allows stylesheets to effect this div without altering the galleria div
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<div id="galleria_main_wrapper">');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<script src="' . $sdmcore->getUserAppDirectoryUrl() . '/galleriaManager/galleria/galleria-1.4.2.min.js"></script>');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<div id="galleria" class="galleria" style="height:650px;">');
            $ignore = array('.', '..', '.DS_Store');
            foreach ($images as $value) {
                if (!in_array($value, $ignore)) {
                    $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<img src="' . $sdmcore->getUserAppDirectoryUrl() . '/galleriaManager/imgs/default/' . $value . '">');
                }
            }

            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '</div><!-- end galleria -->');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '
        <script>
            Galleria.loadTheme(\'' . $sdmcore->getUserAppDirectoryUrl() . '/galleriaManager/galleria/themes/classic/galleria.classic.min.js\');
            Galleria.run(\'.galleria\');
        </script>
        ');
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '</div><!-- end galleria_main_wrapper -->');
        }
        break;
    case 'galleriaManager':
        $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, 'Galleria Manager still under construction.');
        break;
    default:
        // do nothing
        break;
}