<?php

/* Save form handler */
if ($saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') !== null) {
    $budgetJson = $sdmassembler->sdmKind(json_encode(array(
        'budgetTitle' => str_replace('Created New Budget on', 'Budget on', $budgetTitle),
        'budgetID' => $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId'),
        'availableCash' => $saveBudgetForm->sdmFormGetSubmittedFormValue('availableCash'),
        'availableDebit' => $saveBudgetForm->sdmFormGetSubmittedFormValue('availableDebit'),
        'availableCredit' => $saveBudgetForm->sdmFormGetSubmittedFormValue('availableCredit'),
        'categorizedExpenses' => $categorizedExpenses,
    )));

    if (file_put_contents($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '.json', $budgetJson) > 0) {
        $output .= '<div class="budget-message budget-success">"' . str_replace('Created New Budget on', 'Budget on', $budgetTitle) . '" was saved to ' . $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '.json</div>';
    } else {
        error_log('User App "Budget": Attempt to save budget ' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . ' to ' . $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '.json  failed.');
        $output .= '<div class="budget-message budget-error">Attempt to save budget with id "' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '" to following location failed: ' . $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '.json</div>';
    }
}