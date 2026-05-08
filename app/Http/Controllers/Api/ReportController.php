<?php

namespace App\Http\Controllers\Api;

use App\Http\Responses\ApiResponse;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get transactions grouped by day, week, or month
     * 
     * Query parameters:
     * - group: 'day' (default), 'week', 'month'
     * - start_date: Optional, format Y-m-d
     * - end_date: Optional, format Y-m-d
     */
    public function transactions(Request $request): JsonResponse
    {
        $groupBy = $request->query('group', 'day');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Validate group parameter
        if (!in_array($groupBy, ['day', 'week', 'month'])) {
            return ApiResponse::error('Invalid group parameter. Must be: day, week, or month', null, null, 422);
        }

        // Validate date format if provided
        if ($startDate && !$this->isValidDate($startDate)) {
            return ApiResponse::error('Invalid start_date format. Use Y-m-d', null, null, 422);
        }

        if ($endDate && !$this->isValidDate($endDate)) {
            return ApiResponse::error('Invalid end_date format. Use Y-m-d', null, null, 422);
        }

        $userId = $request->user()->id;

        $result = $this->reportService->getGroupedTransactions(
            userId: $userId,
            groupBy: $groupBy,
            startDate: $startDate,
            endDate: $endDate
        );

        return ApiResponse::success(
            $result,
            'Transaction report retrieved successfully'
        );
    }

    /**
     * Validate date format
     */
    private function isValidDate(string $date): bool
    {
        return (bool) \DateTime::createFromFormat('Y-m-d', $date);
    }

    /**
     * Get category breakdown for chart
     * 
     * Query parameters:
     * - type: 'income' (default) or 'expense'
     * - period: 'week', 'month' (default), or 'year'
     * - start_date: Optional, format Y-m-d
     * - end_date: Optional, format Y-m-d
     */
    public function categoryBreakdown(Request $request): JsonResponse
    {
        $type = $request->query('type', 'expense');
        $period = $request->query('period', 'month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Validate type parameter
        if (!in_array($type, ['income', 'expense'])) {
            return ApiResponse::error('Invalid type parameter. Must be: income or expense', null, null, 422);
        }

        // Validate period parameter
        if (!in_array($period, ['week', 'month', 'year'])) {
            return ApiResponse::error('Invalid period parameter. Must be: week, month, or year', null, null, 422);
        }

        // Validate date format if provided
        if ($startDate && !$this->isValidDate($startDate)) {
            return ApiResponse::error('Invalid start_date format. Use Y-m-d', null, null, 422);
        }

        if ($endDate && !$this->isValidDate($endDate)) {
            return ApiResponse::error('Invalid end_date format. Use Y-m-d', null, null, 422);
        }

        try {
            $userId = $request->user()->id;

            $result = $this->reportService->getCategoryBreakdown(
                userId: $userId,
                type: $type,
                period: $period,
                startDate: $startDate,
                endDate: $endDate
            );

            return ApiResponse::success(
                $result,
                'Category breakdown report retrieved successfully'
            );
        } catch (\InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), null, null, 422);
        }
    }
}
