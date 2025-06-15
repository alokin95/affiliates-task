<?php

namespace Unit\Http\Responses;

use App\Http\Responses\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseTest extends TestCase
{
    public function test_status_field_reflects_http_status(): void
    {
        $response = ApiResponse::make(['response' => 'data'], 200);

        $body = json_decode($response->getContent(), true);
        ;
        $this->assertEquals(Response::HTTP_OK, $body['status']);
    }

    public function test_default_status_is_ok(): void
    {
        $response = ApiResponse::make([], 200);
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_OK, $body['status']);
    }

    public function test_error_status_for_400_and_above(): void
    {
        $response = ApiResponse::make(null, 422, null, 'Fail', ['field' => 'err']);
        $this->assertEquals(422, $response->getStatusCode());

        $body = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $body['status']);
        $this->assertEquals('Fail', $body['message']);
        $this->assertEquals(['field' => 'err'], $body['errors']);
    }

    public function test_pagination_meta_is_included(): void
    {
        $items     = [['a'], ['b'], ['c']];
        $paginator = new LengthAwarePaginator($items, 3, 1, 1, ['path' => '/', 'query' => []]);

        $response = ApiResponse::make($items, 200, ApiResponse::fromPaginator($paginator));
        $body     = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('pagination', $body);
        $this->assertSame(1, $body['pagination']['current_page']);
        $this->assertSame(3, $body['pagination']['total']);
    }

    public function test_204_no_content_for_null_payload(): void
    {
        $response = ApiResponse::make(null, Response::HTTP_NO_CONTENT);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertNull(json_decode($response->getContent(), true)['payload']);
        ;
    }
}
