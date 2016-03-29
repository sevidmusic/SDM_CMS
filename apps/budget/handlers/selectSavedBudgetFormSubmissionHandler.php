<?php

/* Select saved budget form handler. */
if ($selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget') !== null) {
    /* Load saved budget data. */
    $savedBudgetData = file_get_contents($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget') . '.json');

    /* Decrypt saved budget data. */
    $decryptedSavedBudgetData = $sdmassembler->sdmNice($savedBudgetData);

    /* Dev Code */
    $sdmassembler->sdmCoreSdmReadArray($decryptedSavedBudgetData);

}