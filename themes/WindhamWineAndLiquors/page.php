<!--
Naming conventions for this theme:
core wrappers: core_wrapper_name | core wrapper names are mixed case, and use underscores for spaces
locked wrappers : locked_camelCaseWrapperName | locked wrapper names are camel case, and are prefixed with an acronym of the theme name
unlocked wrappers : wrapper-name | unlocked wrappers are all lower case and use hyphens for spaces.
-->

<!-- Basic row structure
<div class="row">
    <div class="col-12"></div>
</div>
-->

<!--
Wrapper: #mainMenu
Wrapper Classes: wwl_menu,
Child Wrappers: main-menu
-->
<div id="locked_mainMenuWrapper">
    <div id="main-menu" class="wwl_mainMenu">
        <?php
        echo $sdmassembler->sdmAssemblerGetContentHtml('main-menu');
        ?>
    </div>
</div>

<div class="row">
    <div id="main_content" class="wwl_col-12">
        <?php
        echo $sdmassembler->sdmAssemblerGetContentHtml('main_content');
        ?>
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