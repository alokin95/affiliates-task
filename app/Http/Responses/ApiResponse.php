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
     * @param array<string,mixed>|array<int,array<string,mixed>>|null $payload
     * @param int                                                     $status
     * @param array<string,int>|null                                  $pagination
     * @param string|null                                             $message
     * @param array<string,mixed>|null                                $errors
     *
     * @return JsonResponse
     */
    public static function make(
        array|null $payload,
        int $status = Http::HTTP_OK,
        array|null $pagination = null,
        string|null $message = null,
        array|null $errors = null
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
     * Convert a LengthAwarePaginator to simple pagination metadata.
     *
     * @template TValue
     * @param LengthAwarePaginator<int, TValue> $paginator
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
