<?php
/* Get directory listing of the budgets directory. */
$savedBudgetsDirectoryListing = $sdmassembler->sdmCoreGetDirectoryListing('budget/budgets', 'apps');

/* File names to exclude. */
$excludedListings = array('.', '..', '.DS_Store');

/* Initialize $availableBudgets array | The selectSaveBudgetForm's select form element is built from this array. */
$availableBudgets = array();


$selectSavedBudgetForm = new SdmForm();

/* Build $availableBudgets array from $savedBudgetDirectoryListing. */
foreach ($savedBudgetsDirectoryListing as $savedBudgetId) {
    /* Exclude $excludedListings from $availableBudgets array. */
    if (!in_array($savedBudgetId, $excludedListings)) {
        /* Format budgetId for use as a title. */
        $budgetId = formatBudgetTitle($savedBudgetId);
        switch ($selectSavedBudgetForm->sdmFormGetSubmittedFormValue('selectedBudget')) {
            case $savedBudgetId:
                /* Add budget to $availableBudgets array. */
                $availableBudgets[$budgetId] = 'default_' . $savedBudgetId;
                $defaultSet = true;
                break;
            default:
                /* Add budget to $availableBudgets array. */
                $availableBudgets[$budgetId] = $savedBudgetId;
                break;
        }
    }
}

/* If no budget was selected create default a element. If this element is selected on form submission then a new budget will be generated. */
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