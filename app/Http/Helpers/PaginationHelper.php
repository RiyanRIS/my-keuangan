<?php

namespace App\Http\Helpers;

class PaginationHelper
{
    /**
     * Generate pagination meta from paginated collection
     */
    public static function meta($paginated): array
    {
        return [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'from' => $paginated->firstItem(),
            'to' => $paginated->lastItem(),
        ];
    }

    /**
     * Generate summary meta
     */
    public static function summary($total, $count = null, $average = null): array
    {
        $meta = ['total' => $total];
        
        if ($count !== null) {
            $meta['count'] = $count;
        }
        
        if ($average !== null) {
            $meta['average'] = $average;
        }
        
        return $meta;
    }

    /**
     * Generate filter meta
     */
    public static function filter(array $filters, $total = null): array
    {
        $meta = ['filter' => $filters];
        
        if ($total !== null) {
            $meta['total_filtered'] = $total;
        }
        
        return $meta;
    }

    /**
     * Merge multiple meta
     */
    public static function merge(...$metas): array
    {
        $result = [];
        
        foreach ($metas as $meta) {
            if (is_array($meta)) {
                $result = array_merge($result, $meta);
            }
        }
        
        return $result;
    }
}
