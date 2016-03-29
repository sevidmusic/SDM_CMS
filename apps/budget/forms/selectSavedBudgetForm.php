<?php

$selectSavedBudgetForm = new SdmForm();
$selectSavedBudgetForm->method = 'post';
$selectSavedBudgetForm->formHandler = 'budget';
$selectSavedBudgetForm->submitLabel = 'View Selected Budget';
$selectSavedBudgetForm->formElements = array(
    array(
        'id' => 'selectedBudget',
        'type' => 'select',
        'element' => 'Selected Budget',
        'value' => array(
            'Budget on 03/28/2016 8:51 pm' => '03282016202940',
            'Budget on 04/20/2016 10:42 pm' => '05202066217793',
        ),
        'place' => '0',
    ),
);
$selectSavedBudgetForm->sdmFormBuildForm();
$selectSaveBudgetFormHtml = $selectSavedBudgetForm->sdmFormGetForm();