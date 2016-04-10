<?php

/* Balance Overview Table */
$balanceOverviewTable = '<table class="budget-rounded">
            <thead>Summary of available balance and expenses:</thead>
              <tr class="budget-positive">
                <td>Available Balance:</td>
                <td>$' . $availableBalance . '</td>
              </tr>
              <tr class="budget-negative">
                <td>Total Expenses</td>
                <td>$' . $totalExpenses . '</td>
              </tr>
              <tr class="'.($availableAfterExpenses <= 0 ? 'budget-negative' : 'budget-positive').'">
                <td>Available Balance After Expenses</td>
                <td>$' . $availableAfterExpenses . '</td>
              </tr>
            </table>';