<?php

if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'budget') {
    /**
     * The budget app provides a from for creating a simple budget
     * based on a users available cash, available debit, available
     * credit, and expected expenses.
     */
    $devForm = new SdmForm();

    /* Configure incorporation options. */
    $options = array(
        'wrapper' => 'main_content',
        'incmethod' => 'overwrite',
        'incpages' => array('budget'),
        'ignorepages' => array(),
        'roles' => array('all'),
    );

    /* Include select saved budget form. */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/forms/selectSavedBudgetForm.php');

    /* Inclued select saved budget form handler. */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/handlers/selectSavedBudgetFormSubmissionHandler.php');

    /* Require available balance form. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/forms/availableBalanceForm.php');

    /* Include save budget form. */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/forms/saveBudgetForm.php');

    /* Include saveBudgetFrom handler*/
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/handlers/saveBudgetFormSubmissionHandler.php');

    /* Expenses
       Used to determine total expenses for all categories combined.
       This var must be declared before the categorized expenses table
       or the calculations will break. This array is populated during
       the creation of the categorized expenses table, so declaring it
       after the categorized expenses table would lead to an empty array
       being passed to array_sum(), therefore calculating 0 expenses.
       */
    $expenses = array();

    /* Require categorizedExpensesTable | This must always be the first table loaded! */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/tables/categorizedExpensesTable.php');

    /* Require expensesTable. | This must always be included after the
       categorizedExpensesTable. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/tables/expensesTable.php');

    /* Require calculations. | This must always be included after both the
       categorizedExpensesTable and the expensesTable. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/handlers/calculations.php');

    /* Require calculations. | This must always be the last table included. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/tables/balanceOverviewTable.php');

    /* Budget Title. */
    $budgetTitle = 'Budget on ' . date('F d, Y') . ' at ' . date('g:ia');
    $budgetTitleHtml = '<h4 class="center">' . $budgetTitle . '</h4>';

    /* App Output */
    $output = $selectSaveBudgetFormHtml;
    $output .= $budgetTitleHtml;
    $output .= $availableBalanceFormHtml;
    $output .= $balanceOverviewTable;
    $output .= $categorizedExpensesTable;
    $output .= $expensesTable;
    $output .= $saveBudgetFormHtml;

    /* Incorporate output. */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);
}