<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteProductsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all the rows inside the products table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('products')->delete();

        $this->info('Products table cleared successfully.');
    }
}
