<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetMonthlySales extends Command
{
    protected $signature = 'sales:reset-monthly';
    protected $description = 'Reset sales data at the start of each month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Reset sales data
        DB::table('sales')->truncate(); // Clear all sales data

        $this->info('Sales data has been reset.');
    }
}
