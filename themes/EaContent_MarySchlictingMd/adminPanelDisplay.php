<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 4/23/16
 * Time: 11:07 PM
 */

?>

<!-- #showAdminBar button -->
<button id="showAdminBar" class="adminShowHideButton">Show Admin Bar</button>
<!-- End #showAdminBar button -->

<!-- #adminBarDisplay -->
<div id="adminBarDisplay">

    <!-- #showAdminBar button -->
    <button id="hideAdminBar" class="adminShowHideButton">Hide Admin Bar</button>
    <!-- End #showAdminBar button -->

    <!-- #hideAdminPanel button -->
    <button id="hideAdminPanel" class="adminShowHideButton">Hide Admin Panel</button>
    <!-- End #hideAdminPanel button -->

    <!-- #showAdminPanel button -->
    <button id="showAdminPanel" class="adminShowHideButton">Show Admin Panel</button>
    <!-- End #showAdminPanel button -->

    <!-- #adminPanelDisplay -->
    <div id="adminPanelDisplay">
        <?php
        echo $sdmassembler->sdmAssemblerGetContentHtml('top-menu');
        echo $sdmassembler->sdmAssemblerGetContentHtml('main_content');
        echo $sdmassembler->sdmAssemblerGetContentHtml('side-bar');
        ?>
    </div>
    <!-- End #adminPanelDisplay -->
</div>
<!-- End #adminBarDisplay. -->
