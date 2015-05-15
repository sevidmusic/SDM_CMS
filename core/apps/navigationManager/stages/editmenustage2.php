<?php

$menuId = SdmForm::sdmFormGetSubmittedFormValue('menuId');
$output = '<div id="originalMenuPreview" style="padding:20px;border:3px dashed #777777;border-radius:7px;"><h4>Menu Preview</h4>' . $sdmnms->sdmNmsGetMenuHtml($menuId) . '</div>';
$editMenuSelectMenuForm = new SdmForm();
$editMenuSelectMenuForm->form_method = 'post';
$editMenuSelectMenuForm->form_handler = 'navigationManagerEditMenuStage3';
$editMenuSelectMenuForm->submitLabel = 'Proceed to Edit Menu Items';
$editMenuSelectMenuForm->form_elements = array(
    array(
        'id' => 'menuId',
        'type' => 'hidden',
        'element' => 'Menu Id',
        'value' => $menuId,
        'place' => '0',
    ),
);
$editMenuSelectMenuForm->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output . $editMenuSelectMenuForm->sdmFormGetForm(), array('incpages' => array('navigationManagerEditMenuStage2')));
