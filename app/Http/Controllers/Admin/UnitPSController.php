<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitPS;
use App\Models\UnitPSInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UnitPSController extends Controller
{
    public function index()
    {
        Gate::authorize('access-admin');

        // Always show the 4 fixed master units
        $fixedUnits = UnitPS::getFixedMasterUnits();
        $allUnits = UnitPS::select('id', 'name', 'model', 'brand', 'serial_number', 'price_per_hour', 'stock', 'nama', 'merek', 'nomor_seri', 'harga_per_jam', 'stok', 'foto', 'kondisi')
            ->latest()
            ->withCount('rentalItems')
            ->get()->keyBy('model');

        // Create units array with all 4 fixed units
        $units = [];
        foreach ($fixedUnits as $unitData) {
            $model = $unitData['model'];
            if (isset($allUnits[$model])) {
                // Use existing unit if found
                $unit = $allUnits[$model];
                $unit->available_stock = $unit->instances()->where('status', 'available')->count();
                $unit->total_instances = $unit->instances->count();
                $units[] = $unit;
            } else {
                // Create a placeholder for missing master units
                $units[] = (object) [
                    'id' => null,
                    'name' => $unitData['name'],
                    'model' => $unitData['model'],
                    'brand' => $unitData['brand'],
                    'serial_number' => '',
                    'price_per_hour' => $unitData['default_price'],
                    'stock' => 0,
                    'nama' => $unitData['name'],
                    'merek' => $unitData['brand'],
                    'nomor_seri' => '',
                    'harga_per_jam' => $unitData['default_price'],
                    'stok' => 0,
                    'available_stock' => 0,
                    'total_instances' => 0,
                    'rentalItems_count' => 0,
                    'foto' => null,
                    'kondisi' => null,
                ];
            }
        }

        return view('admin.unitps.index', compact('units'));
    }

    public function create()
    {
        Gate::authorize('access-admin');

        return view('admin.unitps.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('access-admin');
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'merek' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:100'],
            'nomor_seri' => ['required', 'string', 'max:255', 'unique:unit_ps,nomor_seri', 'regex:/^[A-Za-z0-9]+$/'],
            'harga_per_jam' => ['required', 'numeric', 'min:0', 'max:999999'],
            'stok' => ['required', 'integer', 'min:0', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024', 'dimensions:max_width=2000,max_height=2000'],
            'kondisi' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Tersedia,Disewa,Maintenance'],
        ], [
            'nomor_seri.regex' => 'Nomor seri hanya boleh berisi huruf dan angka.',
            'nomor_seri.unique' => 'Nomor seri sudah digunakan.',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/unitps', 'public');
            $validated['foto'] = $path;
        } else {
            unset($validated['foto']);
        }

        // Kompatibilitas kolom lama - mapping field Indonesia ke field database
        $data = [
            'name' => $validated['nama'],
            'brand' => $validated['merek'],
            'model' => $validated['model'],
            'serial_number' => $validated['nomor_seri'],
            'price_per_hour' => $validated['harga_per_jam'],
            'stock' => $validated['stok'],
            'kondisi' => $validated['kondisi'] ?? null,
            'status' => $validated['status'],
            // Also populate Indonesian fields
            'nama' => $validated['nama'],
            'merek' => $validated['merek'],
            'nomor_seri' => $validated['nomor_seri'],
            'harga_per_jam' => $validated['harga_per_jam'],
            'stok' => $validated['stok'],
        ];

        if (isset($validated['foto'])) {
            $data['foto'] = $validated['foto'];
        }

        UnitPS::create($data);
        return redirect()->route('admin.unitps.index')->with('status', 'Unit PS dibuat');
    }

    public function edit(UnitPS $unitp)
    {
        Gate::authorize('access-admin');

        return view('admin.unitps.edit', ['unit' => $unitp]);
    }

    public function update(Request $request, UnitPS $unitp)
    {
        Gate::authorize('access-admin');

        // Check if the model is a valid fixed master unit (allowing variations like "PS5 Hitam", "PS4 Putih", etc.)
        if (! UnitPS::isValidFixedMasterModel($request->input('model'))) {
            return back()->withErrors(['model' => 'Hanya unit PlayStation fixed master yang diperbolehkan (PS2, PS3, PS4, PS5).'])->withInput();
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'merek' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:100'],
            'harga_per_jam' => ['required', 'numeric', 'min:0', 'max:999999'],
            'stok' => ['required', 'integer', 'min:0', 'max:1000'], // Allow 0 stock for master units
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024', 'dimensions:max_width=2000,max_height=2000'],
            'kondisi' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Tersedia,Disewa,Maintenance'],
        ], [
            'serial_numbers.regex' => 'Nomor seri hanya boleh berisi huruf dan angka.',
            'serial_numbers.distinct' => 'Nomor seri harus unik.',
            'serial_numbers.size' => 'Jumlah nomor seri harus sama dengan jumlah stok.',
        ]);

        // Check if any serial numbers already exist (excluding current unit's instances)
        $existingSerials = UnitPSInstance::whereIn('serial_number', $validated['serial_numbers'])
            ->whereHas('unitPS', function ($q) use ($unitp) {
                $q->where('unit_ps.id', '!=', $unitp->id);
            })
            ->get();

        if ($existingSerials->count() > 0) {
            return back()->withErrors([
                'serial_numbers' => 'Beberapa nomor seri sudah digunakan oleh unit lain: '.$existingSerials->pluck('serial_number')->join(', '),
            ])->withInput();
        }

        // Check if a different unit already has the same model (preventing duplicate models)
        $existingUnit = UnitPS::where('model', $validated['model'])->where('id', '!=', $unitp->id)->first();
        if ($existingUnit) {
            return back()->withErrors(['model' => 'Unit dengan model ini sudah ada.'])->withInput();
        }

        // Check if stock is being reduced and if there are rented instances
        $currentStock = $unitp->instances()->count();
        $newStock = $validated['stok'];

        if ($newStock < $currentStock) {
            // Count rented instances
            $rentedCount = $unitp->instances()->where('status', 'rented')->count();

            if ($rentedCount > $newStock) {
                return back()->withErrors(['stok' => 'Tidak bisa mengurangi stok karena ada unit yang sedang disewa.']);
            }
        }

        if ($request->hasFile('foto')) {
            if ($unitp->foto && Storage::disk('public')->exists($unitp->foto)) {
                Storage::disk('public')->delete($unitp->foto);
            }
            $path = $request->file('foto')->store('images/unitps', 'public');
            $validated['foto'] = $path;
        } else {
            unset($validated['foto']);
        }

        // Check if stock is being changed
        $stockChanged = $unitp->stok != $validated['stok'];

        // Update main unit PS record
        $data = [
            'name' => $validated['nama'],
            'brand' => $validated['merek'],
            'model' => $validated['model'],
            'serial_number' => $validated['serial_numbers'][0] ?? '', // Use first serial as main serial, or empty if no stock
            'price_per_hour' => $validated['harga_per_jam'],
            'stock' => $validated['stok'],
            'kondisi' => $validated['kondisi'] ?? null,
            'status' => $validated['status'],
            // Also populate Indonesian fields
            'nama' => $validated['nama'],
            'merek' => $validated['merek'],
            'nomor_seri' => $validated['serial_numbers'][0] ?? '', // Use first serial as main serial, or empty if no stock
            'harga_per_jam' => $validated['harga_per_jam'],
            'stok' => $validated['stok'],
        ];

        if (isset($validated['foto'])) {
            $data['foto'] = $validated['foto'];
        }

        $unitp->update($data);

        // Handle instances based on new serial numbers
        if ($stockChanged) {
            // Remove extra instances if stock is reduced
            if ($newStock < $currentStock) {
                $instancesToRemove = $unitp->instances()
                    ->where('status', 'available')
                    ->orderBy('id', 'desc')
                    ->limit($currentStock - $newStock)
                    ->get();

                foreach ($instancesToRemove as $instance) {
                    $instance->delete();
                }
            }

            // Add or update instances based on the new serial numbers
            $currentInstances = $unitp->instances()->orderBy('id')->get();

            // Update existing instances with new serial numbers
            for ($i = 0; $i < min($newStock, $currentStock); $i++) {
                if (isset($validated['serial_numbers'][$i])) {
                    $currentInstances[$i]->update(['serial_number' => $validated['serial_numbers'][$i]]);
                }
            }

            // Add new instances if stock increased
            if ($newStock > $currentStock) {
                for ($i = $currentStock; $i < $newStock; $i++) {
                    UnitPSInstance::create([
                        'unit_ps_id' => $unitp->id,
                        'serial_number' => $validated['serial_numbers'][$i],
                        'status' => 'available',
                        'condition' => $validated['kondisi'] ?? null,
                    ]);
                }
            }
        } else {
            // Stock didn't change, just update serial numbers
            $instances = $unitp->instances()->orderBy('id')->get();
            for ($i = 0; $i < count($instances); $i++) {
                if (isset($validated['serial_numbers'][$i])) {
                    $instances[$i]->update(['serial_number' => $validated['serial_numbers'][$i]]);
                }
            }
        }

        return redirect()->route('admin.unitps.index')->with('status', 'Unit PS diperbarui');
    }

    public function destroy(UnitPS $unitp)
    {
        Gate::authorize('access-admin');
        $hasActiveRentals = $unitp->rentalItems()
            ->whereHas('rental', function ($q) {
                $q->where('status', '!=', 'returned');
            })
            ->exists();
        if ($hasActiveRentals) {
            return redirect()->route('admin.unitps.index')->with('status', 'Unit PS tidak bisa dihapus karena masih terkait transaksi yang belum dikembalikan');
        }
        $unitp->delete();

        return redirect()->route('admin.unitps.index')->with('status', 'Unit PS dihapus');
    }
}
