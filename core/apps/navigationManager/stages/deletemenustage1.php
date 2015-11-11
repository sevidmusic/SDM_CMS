<?php

$availableMenus = $sdmnms->sdmNmsGenerateMenuPropertiesArray('menuDisplayName', 'menuId');
if (!empty($availableMenus) === true) {
    $deleteMenuFormStage1 = new SdmForm();
    $deleteMenuFormStage1->form_handler = 'navigationManagerDeleteMenuStage2';
    $deleteMenuFormStage1->method = 'post';
    $deleteMenuFormStage1->form_elements = array(
        array(
            'id' => 'menuId',
            'type' => 'select',
            'element' => 'Select A Menu To Delete',
            'value' => $availableMenus,
            'place' => '0',
        ),
    );
    $deleteMenuFormStage1->submitLabel = 'Delete Menu';
    $deleteMenuFormStage1->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());

    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmAssemblerDataObject, '<h3>Delete Menu</h3>' . $deleteMenuFormStage1->sdmFormGetForm(), array('incpages' => array('navigationManagerDeleteMenuStage1')));
} else {
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmAssemblerDataObject, '<h3>Delete Menu</h3><p>There are no menus to delete.</p>', array('incpages' => array('navigationManagerDeleteMenuStage1')));
}