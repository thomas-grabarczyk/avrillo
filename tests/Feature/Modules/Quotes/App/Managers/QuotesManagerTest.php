<?php

namespace Tests\Feature\Modules\Quotes\App\Managers;

use Avrillo\Quotes\App\Managers\KanyeQuotes;
use Avrillo\Quotes\App\Managers\OtherQuotes;
use Avrillo\Quotes\App\Managers\QuotesManager;
use InvalidArgumentException;
use Tests\TestCase;

class QuotesManagerTest extends TestCase
{
    public function test_it_switches_managers(): void
    {
        $baseManager = app(QuotesManager::class);

        // Non existing manager
        $this->expectException(InvalidArgumentException::class);
        $baseManager->get('example');

        // Default manager
        $manager = $baseManager->get();
        $this->assertInstanceOf(KanyeQuotes::class, $manager);

        // Other manager
        $manager = $baseManager->get('other');
        $this->assertInstanceOf(OtherQuotes::class, $manager);

        // See if we can register new managers
        $baseManager->register('yetAnother', OtherQuotes::class);
        $manager = $baseManager->get('yetAnother');
        $this->assertInstanceOf(OtherQuotes::class, $manager);
    }
}
