<?php

$deleteMenuFormStage1 = new SdmForm();
$deleteMenuFormStage1->form_handler = 'navigationManagerDeleteMenuStage2';
$deleteMenuFormStage1->method = 'post';
$deleteMenuFormStage1->form_elements = array(
    array(
        'id' => 'menuId',
        'type' => 'select',
        'element' => 'Select A Menu To Delete',
        'value' => $sdmnms->sdmNmsGenerateMenuPropertiesArray('menuDisplayName', 'menuId'),
        'place' => '0',
    ),
);
$deleteMenuFormStage1->submitLabel = 'Delete Menu';
$deleteMenuFormStage1->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());

$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h3>Delete Menu</h3>' . $deleteMenuFormStage1->sdmFormGetForm(), array('incpages' => array('navigationManagerDeleteMenuStage1')));

