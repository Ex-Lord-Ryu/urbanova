<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class InitializeProductStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:initialize-stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize size stocks for existing products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initializing product size stocks...');

        $products = Product::has('sizes')->get();
        $bar = $this->output->createProgressBar(count($products));

        $totalUpdated = 0;

        foreach ($products as $product) {
            $sizeIds = $product->sizes()->pluck('sizes.id')->toArray();

            foreach ($sizeIds as $sizeId) {
                $existingStock = $product->sizeStocks()->where('size_id', $sizeId)->first();

                if (!$existingStock) {
                    $product->sizeStocks()->create([
                        'size_id' => $sizeId,
                        'stock' => $product->stock // Use global stock
                    ]);

                    $totalUpdated++;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully initialized stock for {$totalUpdated} product-size combinations.");

        return 0;
    }
}
