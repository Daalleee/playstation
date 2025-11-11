<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\UnitPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UnitPSController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-pelanggan');

        // Always show the 4 fixed master units regardless of stock availability
        $fixedUnits = UnitPS::getFixedMasterUnits();

        // Get all existing UnitPS records
        $allUnits = UnitPS::with(['instances'])->get();

        // Create units array with all 4 fixed units
        $units = [];
        foreach ($fixedUnits as $unitData) {
            $baseModel = $unitData['model'];
            $foundUnit = null;

            // Look for existing unit that matches this base model
            foreach ($allUnits as $unit) {
                if (UnitPS::getBaseModel($unit->model) === $baseModel) {
                    $foundUnit = $unit;
                    break;
                }
            }

            if ($foundUnit) {
                // Use existing unit if found
                $units[] = $foundUnit;
            } else {
                // If not found, create a placeholder unit
                $units[] = (object) [
                    'id' => null,
                    'name' => $unitData['name'],
                    'model' => $unitData['model'],
                    'brand' => $unitData['brand'],
                    'price_per_hour' => $unitData['default_price'],
                    'instances' => collect([]),
                    'available_stock' => 0,
                ];
            }
        }

        // Apply filters if any
        if ($request->filled('model')) {
            $units = array_filter($units, function ($unit) use ($request) {
                return $unit->model === $request->model;
            });
        }

        if ($request->filled('brand')) {
            $units = array_filter($units, function ($unit) use ($request) {
                return $unit->brand === $request->brand;
            });
        }

        if ($request->filled('q')) {
            $search = strtolower($request->q);
            $units = array_filter($units, function ($unit) use ($search) {
                return strpos(strtolower($unit->name), $search) !== false ||
                       strpos(strtolower($unit->model), $search) !== false;
            });
        }

        // Convert back to collection if needed for view
        $units = collect($units);

        return view('pelanggan.unitps.index', compact('units'));
    }
}
