<?php

$sdmassembler->sdmAssemblerIncorporateAppOutput('<h3>Deleted Menu:</h3>' . $sdmassembler->sdmNmsDeleteMenu(SdmForm::sdmFormGetSubmittedFormValue('menuId')), array('incpages' => array('navigationManagerDeleteMenuStage2')));
