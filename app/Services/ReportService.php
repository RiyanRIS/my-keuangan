<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Get grouped transactions report with summary
     * 
     * @param int $userId
     * @param string $groupBy 'day', 'week', or 'month'
     * @param string|null $startDate Optional start date (Y-m-d)
     * @param string|null $endDate Optional end date (Y-m-d)
     * @return array
     */
    public function getGroupedTransactions(
        int $userId,
        string $groupBy = 'day',
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        $query = Transaction::whereHas('wallet', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['wallet', 'category', 'toWallet']);

        // Apply date filters
        if ($startDate) {
            $query->where('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('transaction_date', '<=', $endDate);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        // Calculate summary
        $totalIncome = $this->calculateTotal($transactions, 'income');
        $totalExpense = $this->calculateTotal($transactions, 'expense');
        $balance = $totalIncome - $totalExpense;

        $groupedData = match ($groupBy) {
            'day' => $this->groupByDay($transactions),
            'week' => $this->groupByWeek($transactions),
            'month' => $this->groupByMonth($transactions),
            default => $this->groupByDay($transactions),
        };

        return [
            'summary' => [
                'income' => (float) $totalIncome,
                'expense' => (float) $totalExpense,
                'balance' => (float) $balance,
            ],
            'data' => $groupedData,
        ];
    }

    /**
     * Group transactions by day
     */
    private function groupByDay(Collection $transactions): array
    {
        $grouped = $transactions->groupBy(function ($transaction) {
            return $transaction->transaction_date->format('Y-m-d');
        });

        $data = [];

        foreach ($grouped as $date => $dayTransactions) {
            $carbonDate = Carbon::parse($date);
            $data[] = [
                'date' => $carbonDate->format('Y-m-d'),
                'day' => $carbonDate->format('d'),
                'weekday' => $carbonDate->format('D'),
                'month_year' => $carbonDate->format('F Y'),
                'total_income' => $this->calculateTotal($dayTransactions, 'income'),
                'total_expense' => $this->calculateTotal($dayTransactions, 'expense'),
                'total_transaction' => $dayTransactions->count(),
                'items' => $this->formatTransactionItems($dayTransactions),
            ];
        }

        return $data;
    }

    /**
     * Group transactions by week
     */
    private function groupByWeek(Collection $transactions): array
    {
        $grouped = $transactions->groupBy(function ($transaction) {
            $date = $transaction->transaction_date;
            $weekOfMonth = ceil($date->day / 7);
            return $date->format('Y-m') . '-W' . $weekOfMonth;
        });

        $data = [];

        foreach ($grouped as $week => $weekTransactions) {
            // Get the first transaction date to determine week info
            $firstDate = $weekTransactions->first()->transaction_date;
            $weekOfMonth = ceil($firstDate->day / 7);
            $startOfWeek = $weekTransactions->min('transaction_date');
            $endOfWeek = $weekTransactions->max('transaction_date');

            $data[] = [
                'week' => $weekOfMonth,
                'start_date' => $startOfWeek->format('Y-m-d'),
                'end_date' => $endOfWeek->format('Y-m-d'),
                'month_year' => $firstDate->format('F Y'),
                'total_income' => $this->calculateTotal($weekTransactions, 'income'),
                'total_expense' => $this->calculateTotal($weekTransactions, 'expense'),
                'total_transaction' => $weekTransactions->count(),
                'items' => $this->formatTransactionItems($weekTransactions),
            ];
        }

        return $data;
    }

    /**
     * Group transactions by month
     */
    private function groupByMonth(Collection $transactions): array
    {
        $grouped = $transactions->groupBy(function ($transaction) {
            return $transaction->transaction_date->format('Y-m');
        });

        $data = [];

        foreach ($grouped as $month => $monthTransactions) {
            $carbonDate = Carbon::parse($month . '-01');

            $data[] = [
                'month' => strtoupper($carbonDate->format('M')),
                'month_year' => $carbonDate->format('F Y'),
                'start_date' => $carbonDate->format('Y-m-d'),
                'end_date' => $carbonDate->copy()->endOfMonth()->format('Y-m-d'),
                'total_income' => $this->calculateTotal($monthTransactions, 'income'),
                'total_expense' => $this->calculateTotal($monthTransactions, 'expense'),
                'total_transaction' => $monthTransactions->count(),
                'items' => $this->formatTransactionItems($monthTransactions),
            ];
        }

        return $data;
    }

    /**
     * Calculate total for specific transaction type
     */
    private function calculateTotal(Collection $transactions, string $type): float
    {
        return (float) $transactions
            ->filter(function ($transaction) use ($type) {
                return $transaction->type instanceof \App\Enums\TransactionType 
                    ? $transaction->type->value === $type
                    : $transaction->type === $type;
            })
            ->sum('amount');
    }

    /**
     * Format transaction items for response
     */
    private function formatTransactionItems(Collection $transactions): array
    {
        return $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'type' => $transaction->type->value,
                'category' => $transaction->category?->name,
                'note' => $transaction->note,
                'wallet' => $transaction->wallet?->name,
                'amount' => (float) $transaction->amount,
            ];
        })->values()->toArray();
    }

    /**
     * Get category breakdown for chart
     * 
     * @param int $userId
     * @param string $type 'income' or 'expense'
     * @param string $period 'week', 'month', or 'year'
     * @param string|null $startDate Optional start date (Y-m-d)
     * @param string|null $endDate Optional end date (Y-m-d)
     * @return array
     */
    public function getCategoryBreakdown(
        int $userId,
        string $type = 'expense',
        string $period = 'month',
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        // Validate type
        if (!in_array($type, ['income', 'expense'])) {
            throw new \InvalidArgumentException('Type must be income or expense');
        }

        // Determine date range if not provided
        if (!$startDate || !$endDate) {
            [$startDate, $endDate] = $this->getPeriodDateRange($period);
        }

        $query = Transaction::whereHas('wallet', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->where(function ($q) use ($type) {
            if ($type === 'income') {
                $q->where('type', 'income');
            } else {
                $q->whereIn('type', ['expense', 'adjustment']);
            }
        })
        ->whereBetween('transaction_date', [$startDate, $endDate])
        ->with(['category']);

        $transactions = $query->get();

        // Group by category
        $grouped = $transactions->groupBy('category_id');

        $categories = [];
        $totalAmount = 0;
        $totalTransactions = 0;

        foreach ($grouped as $categoryId => $categoryTransactions) {
            $category = $categoryTransactions->first()->category;
            $amount = (float) $categoryTransactions->sum('amount');
            $count = $categoryTransactions->count();

            if ($category && $amount > 0) {
                $categories[] = [
                    'category_id' => $category->id,
                    'category' => $category->name,
                    'icon' => $category->icon ?? 'tag',
                    'color' => $category->color ?? '#6B7280',
                    'amount' => $amount,
                    'transaction_count' => $count,
                ];

                $totalAmount += $amount;
                $totalTransactions += $count;
            }
        }

        // Calculate percentage
        foreach ($categories as &$cat) {
            $cat['percentage'] = $totalAmount > 0 ? round(($cat['amount'] / $totalAmount) * 100, 2) : 0;
        }

        // Sort by amount descending
        usort($categories, function ($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });

        $periodLabel = $this->getPeriodLabel($period, $startDate, $endDate);

        return [
            'filter' => [
                'type' => $type,
                'period' => $period,
                'label' => $periodLabel,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total' => (float) $totalAmount,
                'transaction_count' => $totalTransactions,
            ],
            'categories' => $categories,
        ];
    }

    /**
     * Get period date range
     */
    private function getPeriodDateRange(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'week' => [
                $now->copy()->startOfWeek()->format('Y-m-d'),
                $now->copy()->endOfWeek()->format('Y-m-d'),
            ],
            'month' => [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d'),
            ],
            'year' => [
                $now->copy()->startOfYear()->format('Y-m-d'),
                $now->copy()->endOfYear()->format('Y-m-d'),
            ],
            default => [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d'),
            ],
        };
    }

    /**
     * Get period label
     */
    private function getPeriodLabel(string $period, string $startDate, string $endDate): string
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return match ($period) {
            'week' => $start->format('d M') . ' - ' . $end->format('d M Y'),
            'month' => $start->format('F Y'),
            'year' => $start->format('Y'),
            default => $start->format('F Y'),
        };
    }
}
