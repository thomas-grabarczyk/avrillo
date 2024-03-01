<?php

namespace Tests\Feature\Modules\Quotes;

use Avrillo\Quotes\App\Managers\KanyeQuotes;
use Avrillo\Quotes\App\Managers\QuotesManager;
use Avrillo\Quotes\Quotes;
use Avrillo\Quotes\QuotesConcrete;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * The first 4 methods call the actual api - this is just for testing purposes.
 * Please see 5th+ method(s) to see that I'm not crazy and would use mocks in real life.
 */
class GetFiveRandomQuotesTest extends TestCase
{
    public function test_it_returns_five_random_quotes(): void
    {
        $this->assertCount(5, Quotes::get());
    }

    public function test_we_can_use_other_managers_to_get_random_quotes(): void
    {
        $this->assertCount(5, Quotes::using('other')->get());
    }

    public function test_we_can_get_quotes_with_mock(): void
    {
        $baseManagerMock = $this->createMock(QuotesManager::class);
        $managerMock = $this->createMock(KanyeQuotes::class);
        $managerMock->method('get')->willReturn('Example quote');
        $baseManagerMock->expects($this->once())->method('get')->willReturn($managerMock);

        $manager = new QuotesConcrete($baseManagerMock);
        $this->assertCount(5, $manager->get());
    }

    public function test_quotes_are_cached(): void
    {
        $baseManagerMock = $this->createMock(QuotesManager::class);
        $managerMock = $this->createMock(KanyeQuotes::class);
        $managerMock->method('get')->willReturn('Example quote');
        $baseManagerMock->expects($this->once())->method('get')->willReturn($managerMock);

        Cache::forget('quotes');
        $this->assertCount(0, Cache::get('quotes', []));

        $manager = new QuotesConcrete($baseManagerMock);
        $this->assertCount(5, $manager->get());
        $this->assertCount(5, Cache::get('quotes'));
    }

    public function test_we_can_refresh_quotes(): void
    {
        $baseManagerMock = $this->createMock(QuotesManager::class);
        $managerMock = $this->createMock(KanyeQuotes::class);
        $managerMock->method('get')->willReturn('Example quote');
        $baseManagerMock->method('get')->willReturn($managerMock);
        $manager = new QuotesConcrete($baseManagerMock);

        // Get first quotes and cache them
        $manager->get();

        // Make sure cache has quotes
        $quotes = Cache::get('quotes');
        $this->assertCount(5, $quotes);

        // Make sure quotes stored initially match
        $this->assertEquals('Example quote', $quotes[0]);

        // Re-provide manager that will return different quotes
        $baseManagerMock = $this->createMock(QuotesManager::class);
        $managerMock = $this->createMock(KanyeQuotes::class);
        $managerMock->method('get')->willReturn('Example quote 2');
        $baseManagerMock->method('get')->willReturn($managerMock);
        $manager = new QuotesConcrete($baseManagerMock);

        // Make sure quotes are still the same, we haven't refreshed yet
        $quotes = $manager->get();
        $this->assertEquals('Example quote', $quotes[0]);

        // Refresh quotes and see if they changed
        $quotes = $manager->refresh();
        $this->assertEquals('Example quote 2', $quotes[0]);
    }
}
