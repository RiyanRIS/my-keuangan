<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Success response
     */
    public static function success($data = null, $message = null, $meta = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $statusCode);
    }

    /**
     * Error response
     */
    public static function error($message = null, $data = null, $meta = null, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $statusCode);
    }

    /**
     * Created response
     */
    public static function created($data = null, $message = 'Resource created successfully', $meta = null): JsonResponse
    {
        return self::success($data, $message, $meta, 201);
    }

    /**
     * Validation error response
     */
    public static function validationError($errors, $message = 'Validation failed'): JsonResponse
    {
        return self::error($message, null, $errors, 422);
    }

    /**
     * Unauthorized response
     */
    public static function unauthorized($message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, null, null, 401);
    }

    /**
     * Not found response
     */
    public static function notFound($message = 'Resource not found'): JsonResponse
    {
        return self::error($message, null, null, 404);
    }

    /**
     * Pagination response
     */
    public static function paginated($data, $message = 'Data retrieved successfully', $pagination = null): JsonResponse
    {
        return self::success($data, $message, $pagination);
    }
}

