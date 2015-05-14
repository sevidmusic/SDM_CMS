<?php

$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h3>Deleted Menu:</h3>' . $sdmnms->sdmNmsdeleteMenu(SdmForm::sdmFormGetSubmittedFormValue('menuId')), array('incpages' => array('navigationManagerDeleteMenuStage2')));
?>
