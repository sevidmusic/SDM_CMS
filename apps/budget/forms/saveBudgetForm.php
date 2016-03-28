<?php

/* Save budget form. */
$saveBudgetForm = new SdmForm();
$saveBudgetForm->method = 'post';
$saveBudgetForm->formHandler = 'budget';
$saveBudgetForm->formElements = array(
    array(
        'id' => 'budgetCreationTime',
        'type' => 'hidden',
        'element' => 'Budget Creation Time',
        'value' => time(),
        'place' => '1',
    ),
    array(
        'id' => 'availableCash',
        'type' => 'hidden',
        'element' => 'Available Cash',
        'value' => strval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableCash')),
        'place' => '2',
    ),
    array(
        'id' => 'availableDebit',
        'type' => 'hidden',
        'element' => 'Available Debit',
        'value' => strval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableDebit')),
        'place' => '3',
    ),
    array(
        'id' => 'availableCredit',
        'type' => 'hidden',
        'element' => 'Available Credit',
        'value' => strval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableCredit')),
        'place' => '4',
    ),
    array(
        'id' => 'budgetId',
        'type' => 'hidden',
        'element' => 'Budget Id',
        'value' => strval(date('mdYHis')),
        'place' => '4',
    ),
);
$saveBudgetForm->submitLabel = 'Save Budget';
$saveBudgetForm->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
$saveBudgetFormHtml = $saveBudgetForm->sdmFormGetForm();