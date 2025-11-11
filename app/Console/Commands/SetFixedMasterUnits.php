<?php

namespace App\Console\Commands;

use App\Models\UnitPS;
use App\Models\UnitPSInstance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetFixedMasterUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-fixed-master-units';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up 4 fixed master PlayStation units';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up 4 fixed master PlayStation units...');

        $fixedUnits = UnitPS::getFixedMasterUnits();

        // Temporarily disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // First, remove any existing UnitPS instances to avoid conflicts
        UnitPSInstance::truncate();

        // Remove existing UnitPS records
        UnitPS::truncate();

        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($fixedUnits as $index => $unitData) {
            // Create the master unit
            $unit = UnitPS::create([
                'name' => $unitData['name'],
                'model' => $unitData['model'],
                'brand' => $unitData['brand'],
                'serial_number' => 'MASTER-'.str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'price_per_hour' => $unitData['default_price'],
                'stock' => 0, // Start with 0 stock, admin will set this
                'nama' => $unitData['name'],
                'merek' => $unitData['brand'],
                'nomor_seri' => 'MASTER-'.str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'harga_per_jam' => $unitData['default_price'],
                'stok' => 0,
            ]);

            $this->info("Created master unit: {$unit->name}");
        }

        $this->info('All 4 fixed master PlayStation units (PS2, PS3, PS4, PS5) have been set up!');
        $this->info('Admin can now manage stock, serial numbers, model, and photos for these fixed units.');
    }
}
