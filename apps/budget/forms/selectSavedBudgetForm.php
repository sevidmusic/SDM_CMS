<?php
/* Get directory listing of the budgets directory. */
$savedBudgetsDirectoryListing = $sdmassembler->sdmCoreGetDirectoryListing('budget/budgets', 'apps');

/* File names to exclude. */
$excludedListings = array('.', '..', '.DS_STORE');

/* Initialize $availableBudgets array | The selectSaveBudgetForm's select form element is built from this array. */
$availableBudgets = array();


$selectSavedBudgetForm = new SdmForm();


/*
 * For now use an iterator to distinguish budgets. Really, the budget title should be used...
 * @todo : Use budget title instead of iterator to differentiate budgets in select form.
 */
$selectBudgetElementIterator = 1;

/* Build $availableBudgets array from $savedBudgetDirectoryListing. */
foreach ($savedBudgetsDirectoryListing as $savedBudgetId) {
    /* Exclude $excludedListings from $availableBudgets array. */
    if (!in_array($savedBudgetId, $excludedListings)) {
        $budgetId = str_replace('.json', '', $savedBudgetId);
        switch ($selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget')) {
            case $budgetId:
                /* Add budget to $availableBudgets array. */
                $availableBudgets['Budget ' . $selectBudgetElementIterator] = 'default_' . $budgetId;
                $defaultSet = true;
                break;
            default:
                /* Add budget to $availableBudgets array. */
                $availableBudgets['Budget ' . $selectBudgetElementIterator] = $budgetId;
                break;
        }

        /* Increase iterator. */
        $selectBudgetElementIterator++;
    }
}

/* If no budget was selected create default a element. */
if (!isset($defaultSet) || $defaultSet !== true) {
    /* Create default select element in case no budget has been selected. | Can be used to get an empty budget. */
    $availableBudgets['-- Create New Budget --'] = 'default_';
} else {
    /* Create default select element in case no budget has been selected. | Can be used to get an empty budget. */
    $availableBudgets['-- Create New Budget --'] = '';
}


$selectSavedBudgetForm->method = 'post';
$selectSavedBudgetForm->formHandler = 'budget';
$selectSavedBudgetForm->submitLabel = 'View Selected Budget';
$selectSavedBudgetForm->formElements = array(
    array(
        'id' => 'selectedBudget',
        'type' => 'select',
        'element' => 'Selected Budget',
        'value' => (empty($availableBudgets) ? array('<i>There are not any saved budgets...</i>' => null) : $availableBudgets),
        'place' => '0',
    ),
);
$selectSavedBudgetForm->sdmFormBuildForm();
$selectSaveBudgetFormHtml = $selectSavedBudgetForm->sdmFormGetForm();