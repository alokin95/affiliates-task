<?php

namespace Tests\Unit\Infrastructure;

use App\Core\Exception\FileParseException;
use App\Core\Storage\FileReaderInterface;
use App\DTOs\AffiliateDTO;
use App\Infrastructure\FileAffiliateSource;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class FileAffiliateSourceTest extends TestCase
{
    /**
     * @throws Exception
     */
    private function readerMockReturning(string $content): FileReaderInterface
    {
        $mock = $this->createMock(FileReaderInterface::class);
        $mock->method('read')->willReturn($content);
        return $mock;
    }

    /**
     * @throws Exception
     */
    public function test_reads_and_parses_valid_json_lines(): void
    {
        $jsonLines = implode("\n", [
            json_encode(['affiliate_id' => 1, 'name' => 'Company 1', 'latitude' => 53.0, 'longitude' => -6.0]),
            json_encode(['affiliate_id' => 2, 'name' => 'Company 2',   'latitude' => 54.0, 'longitude' => -7.0]),
        ]);

        $source = new FileAffiliateSource(
            reader: $this->readerMockReturning($jsonLines),
            filePath: 'affiliates.txt'
        );

        $affiliates = $source->getAll();

        $this->assertCount(2, $affiliates);
        $this->assertContainsOnlyInstancesOf(AffiliateDTO::class, $affiliates);
        $this->assertSame(1, $affiliates[0]->affiliateId);
        $this->assertSame('Company 2', $affiliates[1]->name);
    }

    /**
     * @throws Exception
     */
    public function test_skips_invalid_json_lines(): void
    {
        $lines = [
            json_encode(['affiliate_id' => 1, 'name' => 'Company 1', 'latitude' => 53.0, 'longitude' => -6.0]),
            'THIS_IS_NOT_JSON',
            json_encode(['affiliate_id' => 3, 'name' => 'Company 2',   'latitude' => 55.0, 'longitude' => -8.0]),
        ];

        $source = new FileAffiliateSource(
            reader: $this->readerMockReturning(implode("\n", $lines)),
            filePath: 'affiliates.txt'
        );

        $affiliates = $source->getAll();

        $this->assertCount(2, $affiliates);
        $this->assertEquals([1, 3], array_map(fn (AffiliateDTO $d): int => $d->affiliateId, $affiliates));
    }

    /**
     * @throws Exception
     */
    public function test_throws_exception_on_missing_required_key(): void
    {
        $incomplete = json_encode(['affiliate_id' => 1, 'latitude' => 53.0, 'longitude' => -6.0]);

        $source = new FileAffiliateSource(
            reader: $this->readerMockReturning($incomplete),
            filePath: 'affiliates.txt'
        );

        $this->expectException(FileParseException::class);
        $this->expectExceptionMessage("Missing required key 'name' on line 1");

        $source->getAll();
    }
}
