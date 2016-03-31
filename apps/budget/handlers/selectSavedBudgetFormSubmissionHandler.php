<?php
/* Select saved budget form handler. */
if ($selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget') !== null && strlen($selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget')) !== 0) {
    /* Load saved budget data. */
    $savedBudgetData = file_get_contents($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget') . '.json');

    /* Decrypt saved budget data. */
    $decryptedSavedBudgetData = $sdmassembler->sdmNice($savedBudgetData);

    /* Saved budget. */
    $savedBudget = json_decode($decryptedSavedBudgetData);

}