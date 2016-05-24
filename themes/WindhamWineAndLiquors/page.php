<!--
Naming conventions for this theme:
core wrappers: core_wrapper_name | core wrapper names are mixed case, and use underscores for spaces
locked wrappers : locked_camelCaseWrapperName | locked wrapper names are camel case, and are prefixed"wwl_"
unlocked wrappers : wrapper-name | unlocked wrapper names are all lower case and use hyphens for spaces.
classes : wwl_className | classes are camel case and are prefixed with"wwl_"
-->
<div id="locked_facebookIcon">
    <a href="https://www.facebook.com/Windham-Wine-Liquors-129183917169813/info/?tab=overview">
        <img id="facebook-icon"
             src="<?php echo $sdmassembler->sdmCoreGetCurrentThemeDirectoryUrl(); ?>/media/facebookIcon.png"/>
        <div id="locked_facebookText">Check Us Out On Facebook</div>
    </a>
</div>
<div id="locked_siteWrapper">
    <div class="border row">
        <div id="locked_headerWrapper" class="border wwl_column20">
            <div id="header"><h1><a href="<?php echo $sdmassembler->sdmCoreGetRootDirectoryUrl(); ?>/index.php">Windham
                        Wine &amp; Liquors</a></h1></div>
        </div>
    </div>
    <div class="border row">
        <div id="locked_mainMenuWrapper" class="border wwl_column20">
            <div id="main-menu" class="border">
                <?php
                echo $sdmassembler->sdmAssemblerGetContentHtml('main-menu');
                ?>
            </div>
        </div>
    </div>

    <div class="border row">
        <div id="locked_mainContentWrapper" class="border wwl_column20">
            <div id="main_content">
                <?php
                echo $sdmassembler->sdmAssemblerGetContentHtml('main_content');
                ?>
            </div>
        </div>

    </div>
    <div class="border row">
        <div id="locked_footerWrapper" class="border wwl_column20">
            <div id="locked_footer">Windham Wine &amp; Liquors &copy; 2016 All Rights Reserved</div>
        </div>

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
</div>