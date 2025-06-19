<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductVariant;
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
    protected $description = 'Initialize variant stocks for existing products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initializing product variants and stocks...');

        $products = Product::has('sizes')->get();
        $bar = $this->output->createProgressBar(count($products));

        $totalUpdated = 0;

        foreach ($products as $product) {
            $sizeIds = $product->sizes()->pluck('sizes.id')->toArray();

            foreach ($sizeIds as $sizeId) {
                $existingVariant = $product->variants()->where('size_id', $sizeId)->first();

                if (!$existingVariant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id' => $sizeId,
                        'color_id' => null,
                        'price' => $product->base_price, // Use base price
                        'stock' => $product->stock // Use global stock
                    ]);

                    $totalUpdated++;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully initialized {$totalUpdated} product variants with stock.");

        return 0;
    }
}
