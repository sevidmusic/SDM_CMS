<!--
Naming conventions for this theme:
core wrappers: core_wrapper_name | core wrapper names are mixed case, and use underscores for spaces
locked wrappers : locked_camelCaseWrapperName | locked wrapper names are camel case, and are prefixed "wwl_"
unlocked wrappers : wrapper-name | unlocked wrapper names are all lower case and use hyphens for spaces.
classes : wwl_className | classes are camel case and are prefixed with "wwl_"
-->
<!--
# Wrapper Structure | Listed In Order #
# {WRAPPER NAME | CLASSES  | {CHILD WRAPPER | CHILD WRAPPER'S CLASSES}, {...} } #
----------------------------------------------------------------------
{#locked_mainMenuWrapper | none | {#main-menu | .wwl_mainMenu, .wwl_menuFont}}
----------------------------------------------------------------------
{#main-menu | .wwl_mainMenu, .wwl_menuFont | {none} }
----------------------------------------------------------------------
{#main_content | none | {none} }
----------------------------------------------------------------------
{#Sdm_Cms_Core_Output | none | {none} }
----------------------------------------------------------------------

----------------------------------------------------------------------
-->

<div class="row">
    <div id="locked_mainMenuWrapper" class="wwl_col">
        <div id="main-menu" class="wwl_mainMenu wwl_menuFont">
            <?php
            echo $sdmassembler->sdmAssemblerGetContentHtml('main-menu');
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div id="main_content">
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