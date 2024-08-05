<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\PricingRule;
use App\Services\CheckoutService;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\PricingRuleRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $itemRepository;
    private $pricingRuleRepository;
    private $checkoutService;

    protected function setUp(): void
    {
        parent::setUp();

        // Resolve the repositories from the service container
        $this->itemRepository = $this->app->make(ItemRepositoryInterface::class);
        $this->pricingRuleRepository = $this->app->make(PricingRuleRepositoryInterface::class);
    }

    /**
     * Helper method to get the total price.
     *
     * @param string $items
     * @return int
     */
    protected function price(string $items): int
    {
        // Instantiate the CheckoutService with mocked repositories
        $this->checkoutService = new CheckoutService(
            $this->itemRepository,
            $this->pricingRuleRepository
        );

        foreach (str_split($items) as $item) {
            $this->checkoutService->scan($item);
        }
        return $this->checkoutService->total();
    }

    public function testTotals()
    {
        // $this->assertEquals(0, $this->price(""));
        $this->assertEquals(50, $this->price("A"));
        $this->assertEquals(80, $this->price("AB"));
        $this->assertEquals(115, $this->price("CDBA"));

        $this->assertEquals(100, $this->price("AA")); // No rule applied for 2 A's
        $this->assertEquals(130, $this->price("AAA")); // Rule applied: 3 A's for 130
        $this->assertEquals(180, $this->price("AAAA")); // 3 A's for 130 + 1 A for 50
        $this->assertEquals(230, $this->price("AAAAA")); // 3 A's for 130 + 3 A's for 130 + 1 A for 50
        $this->assertEquals(260, $this->price("AAAAAA")); // 3 A's for 130 + 3 A's for 130 + 3 A's for 130

        $this->assertEquals(160, $this->price("AAAB")); // 3 A's for 130 + 1 A for 50 + 1 B for 30
        $this->assertEquals(175, $this->price("AAABB")); // 3 A's for 130 + 2 A's for 100 + 2 B's for 45
        $this->assertEquals(190, $this->price("AAABBD")); // 3 A's for 130 + 2 A's for 100 + 2 B's for 45 + 1 D for 15
        $this->assertEquals(190, $this->price("DABABA")); // 1 D for 15 + 3 A's for 130 + 2 B's for 45
    }

    public function testIncremental()
    {
        // Instantiate the CheckoutService with mocked repositories
        $co = $this->checkoutService = new CheckoutService(
            $this->itemRepository,
            $this->pricingRuleRepository
        );

        $this->assertEquals(0, $co->total());

        $co->scan("A");
        $this->assertEquals(50, $co->total());

        $co->scan("B");
        $this->assertEquals(80, $co->total());

        $co->scan("A");
        $this->assertEquals(130, $co->total()); // 3 A's for 130

        $co->scan("A");
        $this->assertEquals(160, $co->total()); // 4 A's (3 for 130 + 1 for 50)

        $co->scan("B");
        $this->assertEquals(175, $co->total()); // 4 A's (3 for 130 + 1 for 50) + 2 B's for 45
    }
}
