<?php

if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'budget') {
    /* Make sure budgets directory exists. */
    if (is_dir($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets/') === false) {
        mkdir($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/budgets');
    }

    /* Require budgetFunctions.php */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/includes/budgetFunctions.php');

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

    /* Initialize $output var | Provides a common var for app output. */
    $output = '';

    /* Require select saved budget form. This should be included before anything else. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/forms/selectSavedBudgetForm.php');

    /* Require select saved budget form handler. This should be included after the select
     * saved budget form, and before anything else.
     */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/handlers/selectSavedBudgetFormSubmissionHandler.php');

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

    /* Require available balance form. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/forms/availableBalanceForm.php');

    /* Require expensesTable. | This must always be included after the
       categorizedExpensesTable. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/tables/expensesTable.php');

    /* Require calculations. | This must always be included after both the
       categorizedExpensesTable and the expensesTable. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/handlers/calculations.php');

    /* Require balanceOverviewTable. | This must always be the last table included. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/tables/balanceOverviewTable.php');

    /* Budget Title. Must be declared before requiring saveBudgetForm and savedBudgetFormHandler */
    if (isset($savedBudget->budgetTitle) === true) {
        $budgetTitle = $savedBudget->budgetTitle;
    } elseif ($availableBalanceForm->sdmFormGetSubmittedFormValue('budgetTitle') !== null) {
        $budgetTitle = str_replace('Created New Budget on', '', $availableBalanceForm->sdmFormGetSubmittedFormValue('budgetTitle'));
    } else {
        $budgetTitle = 'Created New Budget on ' . date('F d, Y') . ' at ' . date('g:ia');
    }

    /* Assemble budgetTitleHtml */
    $budgetTitleHtml = $sdmassembler->sdmAssemblerAssembleHtmlElement($budgetTitle, array('elementType' => 'h2', 'classes' => array((isset($savedBudget->budgetTitle) === true || $availableBalanceForm->sdmFormGetSubmittedFormValue('budgetTitle') !== null ? 'budget-center' : 'budget-message budget-success'))));

    /* Require save budget form. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/forms/saveBudgetForm.php');

    /* Require saveBudgetFrom handler*/
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/budget/handlers/saveBudgetFormSubmissionHandler.php');

    $budgetContainerAttributes = array(
        'classes' => array(
            'budget',
            'budget-container',
        ),
        'styles' => array(
            'text-shadow: none',
        ),

    );
    /* App Output */
    $output .= $budgetTitleHtml;
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($selectSaveBudgetFormHtml, $budgetContainerAttributes);
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($balanceOverviewTable, $budgetContainerAttributes);
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($availableBalanceFormHtml, $budgetContainerAttributes);
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($categorizedExpensesTable, $budgetContainerAttributes);
    //$output .= useContainer($addExpenseFormHtml);
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($expensesTable, $budgetContainerAttributes);
    $output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($saveBudgetFormHtml, $budgetContainerAttributes);

    /* Incorporate output. */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options, $budgetContainerAttributes);
}