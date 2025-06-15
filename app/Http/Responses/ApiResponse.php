<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response as Response;

final class ApiResponse
{
    /**
     * Build a standardized JSON response.
     *
     * @param array|null $payload           Typed array representation of DTOs or single DTO
     * @param int        $status            HTTP status code (200, 204, 400, etc.)
     * @param array|null $pagination        Pagination info: ['current_page'=>..., ...]
     * @param string|null $message          Optional message
     * @param array|null $errors            Validation or domain errors
     *
     * @return JsonResponse
     */
    public static function make(
        array|null $payload,
        int $status = 200,
        array|null $pagination = null,
        string|null $message = null,
        array|null $errors = null
    ): JsonResponse {
        $response = [
            'status'     => $status < 400 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            'message'    => $message,
            'payload'    => $payload,
            'pagination' => $pagination,
            'errors'     => $errors,
        ];

        return response()->json($response, $status);
    }

    /**
     * Convert a LengthAwarePaginator to a simple pagination metadata array.
     *
     * @param LengthAwarePaginator $paginator
     * @return array{
     *     current_page: int,
     *     per_page: int,
     *     total: int,
     *     last_page: int
     * }
     */
    public static function fromPaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ];
    }
}
