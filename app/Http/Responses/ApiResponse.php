<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response as Http;

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
        ?array $payload,
        int $status = Http::HTTP_OK,
        ?array $pagination = null,
        ?string $message = null,
        ?array $errors = null
    ): JsonResponse {
        $body = [
            'status'     => $status < 400 ? Http::HTTP_OK : Http::HTTP_BAD_REQUEST,
            'message'    => $message,
            'payload'    => $payload,
            'pagination' => $pagination,
            'errors'     => $errors,
        ];

        return new JsonResponse($body, $status);
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
