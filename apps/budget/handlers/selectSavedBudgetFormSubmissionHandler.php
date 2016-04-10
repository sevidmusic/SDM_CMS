<?php
/* Select saved budget form handler. */
if ($selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget') !== null && strlen($selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget')) !== 0) {
    /* Load saved budget data. */
    $savedBudgetData = file_get_contents($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget'));

    if ($savedBudgetData !== false) {
        $output .= '<div class="budget-message budget-success">Budget "' . $selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget') . '" loaded successfully</div>';
    } else {
        error_log('User App "Budget": Attempt to load budget "' . $selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget') . '" failed.');
        $output .= '<div class="budget-message budget-error">An error occured and the budget could not be loaded.</div>';
    }

    /* Decrypt saved budget data. */
    $decryptedSavedBudgetData = $sdmassembler->sdmNice($savedBudgetData);

    /* Saved budget. */
    $savedBudget = json_decode($decryptedSavedBudgetData);

}