<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\PricingRuleRepositoryInterface;
use App\Services\CheckoutServiceInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CheckoutCommand
 * Handles the checkout process from the command line.
 */
class CheckoutCommand extends Command
{
    protected $signature = 'checkout:run';
    protected $description = 'Run the checkout process from the command line';

    private $itemRepository;
    private $pricingRuleRepository;
    private $checkoutService;
    private $io;

    /**
     * CheckoutCommand constructor.
     *
     * @param ItemRepositoryInterface $itemRepository
     * @param PricingRuleRepositoryInterface $pricingRuleRepository
     * @param CheckoutServiceInterface $checkoutService
     */
    public function __construct(
        ItemRepositoryInterface $itemRepository,
        PricingRuleRepositoryInterface $pricingRuleRepository,
        CheckoutServiceInterface $checkoutService
    ) {
        parent::__construct();
        $this->itemRepository = $itemRepository;
        $this->pricingRuleRepository = $pricingRuleRepository;
        $this->checkoutService = $checkoutService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->io = new SymfonyStyle($this->input, $this->output);
        $this->io->title('Welcome to the Checkout System');
        $this->io->note('Type "done" when you are finished adding items.');

        try {
            $items = $this->itemRepository->getAll();
            $itemSkus = $items->pluck('sku')->implode(', ');

            while (true) {
                $sku = $this->io->ask('Enter item name (' . $itemSkus . ')');

                if (strtolower($sku) === 'done') {
                    break;
                }

                try {
                    $this->checkoutService->scan($sku);
                    $this->printCurrentStatus();
                } catch (\Exception $e) {
                    $this->io->error($e->getMessage());
                }
            }

            $this->printSummary();
        } catch (\Exception $e) {
            $this->io->error('Error during checkout: ' . $e->getMessage());
        }

        return 0;
    }

    /**
     * Print the current status of the checkout in a table format.
     *
     * @return void
     */
    private function printCurrentStatus()
    {
        $this->io->section('Current Status');

        $items = $this->checkoutService->getScannedItems();
        $subtotals = $this->checkoutService->getSubtotals();
        $rulesApplied = $this->checkoutService->getAppliedRules();

        $rows = [];
        foreach ($items as $sku => $quantity) {
            $pricePerUnit = $this->itemRepository->findBySku($sku)->unit_price;
            $subtotal = $subtotals[$sku];
            $rules = $rulesApplied[$sku] ?? [];

            $ruleDescriptions = [];
            foreach ($rules as $rule) {
                $ruleDescriptions[] = "{$rule['quantity']} for " . $rule['price'];
            }
            $rulesText = $ruleDescriptions ? implode(', ', $ruleDescriptions) : 'None';

            $rows[] = [
                $sku,
                $quantity,
                $pricePerUnit,
                $subtotal,
                $rulesText,
            ];
        }

        $this->io->table(
            ['Item', 'Quantity', 'Price per Unit', 'Subtotal', 'Rules Applied'],
            $rows
        );

        $this->io->success("Total: " . $this->checkoutService->total());
    }

    /**
     * Print the final summary of the checkout.
     *
     * @return void
     */
    private function printSummary()
    {
        $this->io->success('Checkout Summary');
        $this->printCurrentStatus();
    }
}
