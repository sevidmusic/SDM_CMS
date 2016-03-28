<?php

if($sdmassembler->sdmCoreDetermineRequestedPage() === 'budget') {
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

    /* Include available balance form. */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/forms/availableBalanceForm.php');

    /* Include save budget form. */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/forms/saveBudgetForm.php');

    /* Expenses
       Used to determine total expenses for all categories combined.
       This var must be declared before the categorized expenses table
       or the calculations will break. This array is populated during
       the creation of the categorized expenses table, so declaring it
       after the categorized expenses table would lead to an empty array
       being passed to array_sum(), therefore calculating 0 expenses.
       */
    $expenses = array();

    /* Include categorizedExpensesTable | This must always be the first table loaded! */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/tables/categorizedExpensesTable.php');

    /* Include expensesTable. | This must always be included after the
       categorizedExpensesTable. */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/tables/expensesTable.php');

    /* Include calculations. | This must always be included after both the
       categorizedExpensesTable and the expensesTable. */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/handlers/calculations.php');

    /* Include calculations. | This must always be the last table included. */
    include_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/tables/balanceOverviewTable.php');

    /* Budget Title. */
    $budgetTitle = '<h4 class="center">Budget on ' . date('F d, Y') . ' at ' . date('g:ia') . '</h4>';

    /* App Output */
    $output = $budgetTitle;
    $output .= $availableBalanceFormHtml;
    $output .= $balanceOverviewTable;
    $output .= $categorizedExpensesTable;
    $output .= $expensesTable;
    $output .= $saveBudgetFormHtml;

    /* Save form handler */

    /* Incorporate output. */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);
}