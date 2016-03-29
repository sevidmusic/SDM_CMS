<?php

/* Save form handler */
if ($saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') !== null) {
    $budgetJson = $sdmassembler->sdmKind(json_encode(array(
        'budgetTitle' => $budgetTitle,
        'budgetID' => $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId'),
        'availableCash' => $saveBudgetForm->sdmFormGetSubmittedFormValue('availableCash'),
        'availableDebit' => $saveBudgetForm->sdmFormGetSubmittedFormValue('availableDebit'),
        'availableCredit' => $saveBudgetForm->sdmFormGetSubmittedFormValue('availableCredit'),
        'categorizedExpenses' => $categorizedExpenses,
    )));

    if (file_put_contents($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '.json', $budgetJson) > 0) {
        $output .= '<div style="color:springgreen;">"' . $budgetTitle . '" was saved to "' . $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '.json"</div>';
    } else {
        error_log('User App "Budget": Attempt to save budget ' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . ' to ' . $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '.json  failed.');
        $output .= '<div style="color:red;">Attempt to save budget with id "' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '" to following location failed:<br><br>"' . $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/' . $saveBudgetForm->sdmFormGetSubmittedFormValue('budgetId') . '.json"</div>';
    }


    // DEV CODE //
    $sdmassembler->sdmCoreSdmReadArray(
        array(
            'Budget Data (Encrypted by default for security)' => $budgetJson,
            'Decrypted Budget Data' => $sdmassembler->sdmNice($budgetJson),
        )
    );
}