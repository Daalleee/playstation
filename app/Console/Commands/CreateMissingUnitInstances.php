<?php

namespace App\Console\Commands;

use App\Models\UnitPS;
use App\Models\UnitPSInstance;
use Illuminate\Console\Command;

class CreateMissingUnitInstances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-missing-unit-instances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating missing UnitPS instances...');

        $unitPSModels = UnitPS::all();

        foreach ($unitPSModels as $unitPS) {
            // Get current number of instances for this unit
            $currentInstancesCount = $unitPS->instances()->count();

            // Get the intended stock from the unitPS record
            $targetStock = $unitPS->stock ?? 1;

            if ($currentInstancesCount < $targetStock) {
                $this->info("Creating {$targetStock} instances for UnitPS: {$unitPS->name}");

                // Create missing instances
                for ($i = $currentInstancesCount; $i < $targetStock; $i++) {
                    $instanceSerial = $unitPS->serial_number.'-'.str_pad($i + 1, 3, '0', STR_PAD_LEFT);

                    // Ensure the serial doesn't already exist
                    $count = 1;
                    $finalSerial = $instanceSerial;
                    while (UnitPSInstance::where('serial_number', $finalSerial)->exists()) {
                        $finalSerial = $unitPS->serial_number.'-'.str_pad($i + 1 + $count * 1000, 3, '0', STR_PAD_LEFT);
                        $count++;
                    }

                    UnitPSInstance::create([
                        'unit_ps_id' => $unitPS->id,
                        'serial_number' => $finalSerial,
                        'status' => 'available',
                        'condition' => $unitPS->kondisi ?? 'Baik',
                    ]);
                }

                $this->info("Created instances for UnitPS: {$unitPS->name}");
            }
        }

        $this->info('All missing UnitPS instances have been created!');
    }
}
