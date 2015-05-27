<?php

$availableMenus = $sdmnms->sdmNmsGenerateMenuPropertiesArray('menuDisplayName', 'menuId');
if (!empty($availableMenus) === TRUE) {
    $editMenuSelectMenuForm = new SdmForm();
    $editMenuSelectMenuForm->form_method = 'post';
    $editMenuSelectMenuForm->form_handler = 'navigationManagerEditMenuStage2';
    $editMenuSelectMenuForm->submitLabel = 'Proceed to Edit Menu Items';
    $editMenuSelectMenuForm->form_elements = array(
        array(
            'id' => 'menuId',
            'type' => 'select',
            'element' => 'Select A Menu To Edit',
            'value' => $availableMenus,
            'place' => '0',
        ),
    );
    $editMenuSelectMenuForm->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h3>Which menu do you wish to edit?</h3>' . $editMenuSelectMenuForm->sdmFormGetForm(), array('incpages' => array('navigationManagerEditMenuStage1')));
} else {
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h3>There are no menus to edit.</h3>', array('incpages' => array('navigationManagerEditMenuStage1')));
}