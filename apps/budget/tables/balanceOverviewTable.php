<?php

/* Balance Overview Table */
$balanceOverviewTable = '<table class="rounded">
            <thead>Summary of available balance and expenses:</thead>
              <tr class="positive">
                <td>Available Balance:</td>
                <td>$' . $availableBalance . '</td>
              </tr>
              <tr class="negative">
                <td>Total Expenses</td>
                <td>$' . $totalExpenses . '</td>
              </tr>
              <tr class="' . ($availableAfterExpenses > 0 ? 'positive' : 'negative') . '">
                <td>Available Balance After Expenses</td>
                <td>$' . $availableAfterExpenses . '</td>
              </tr>
            </table>';