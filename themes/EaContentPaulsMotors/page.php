<?php
/**
 * This file defines the basic html structure for the sdmSimple theme.
 */
?>

<div id="main_content">
    <?php
    echo $sdmassembler->sdmAssemblerGetContentHtml('main_content');
    ?>
</div>
<!--
  Special wrapper provided by Sdm Cms Core to hold Sdm Cms Core Output
  This wrapper is meant to hold core and user app output
  that is not part of the page. For instance, if an app needs to generate
  a message to the user this core wrapper is a good place to output the message.
-->
<div id="Sdm_Cms_Core_Output">
    <?php
    echo $sdmassembler->sdmAssemblerGetContentHtml('Sdm_Cms_Core_Output');
    ?>
</div>