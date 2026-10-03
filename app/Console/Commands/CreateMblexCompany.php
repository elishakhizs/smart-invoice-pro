<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;

class CreateMblexCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:create-mblex-company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the MBLEX company';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Company::create([
            'name' => 'MBLEX',
            'slug' => 'mblex',
        ]);
        $this->info('MBLEX company created successfully.');
    }
}
