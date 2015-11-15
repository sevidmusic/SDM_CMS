<?php

/**
 * This form is where the number of menu items is determined.
 * The user will then be directed to configure the amount of number items
 * determined by this form.
 */
$addMenuFormStage1 = new SdmForm();
$addMenuFormStage1->form_method = 'post';
$addMenuFormStage1->form_handler = 'navigationManagerAddMenuStage2';
$addMenuFormStage1->submitLabel = 'Proceed to Edit Menu Items';
$addMenuFormStage1->form_elements = array(
    array(
        'id' => 'number_of_menu_items',
        'type' => 'select',
        'element' => 'How manu menu items should this menu have. (you can add or delete menu items later so just pick a number you think will work initially)',
        'value' => rangeArray(1, 50),
        'place' => '0',
    ),
    array(
        'id' => 'menuItem',
        'type' => 'hidden',
        'element' => 'Menu Item',
        'value' => 1, // tracks which menu item is being configured | the first menu item form will always set the properties of the first menu item so we always start with menu item 1
        'place' => '1',
    ),
);
$addMenuFormStage1->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
$sdmassembler->sdmAssemblerIncorporateAppOutput('<h3>How many menu items will this menu have?</h3>' . $addMenuFormStage1->sdmFormGetForm(), array('incpages' => array('navigationManagerAddMenuStage1')));
