<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Cart;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['admin', 'kasir', 'pemilik', 'pelanggan'];

        foreach ($roles as $role) {
            User::firstOrCreate(
                ['email' => $role.'@gmail.com'],
                [
                    'name' => ucfirst($role),
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'phone' => '08123456789',
                    'address' => 'Alamat '.$role,
                ]
            );
        }

        // Seed Unit PS (tersedia untuk disewa)
        $units = [
            ['name' => 'PS4 Slim', 'brand' => 'Sony', 'model' => 'PS4', 'serial_number' => 'PS4-1001', 'price_per_hour' => 15000, 'stok' => 3, 'status' => 'available'],
            ['name' => 'PS5 Standard', 'brand' => 'Sony', 'model' => 'PS5', 'serial_number' => 'PS5-2001', 'price_per_hour' => 25000, 'stok' => 2, 'status' => 'available'],
            ['name' => 'PS3 Super Slim', 'brand' => 'Sony', 'model' => 'PS3', 'serial_number' => 'PS3-3001', 'price_per_hour' => 10000, 'stok' => 1, 'status' => 'available'],
        ];
        foreach ($units as $u) {
            UnitPS::updateOrCreate(
                ['serial_number' => $u['serial_number']],
                $u
            );
        }

        // Seed Games
        $games = [
            ['judul' => 'FIFA 24', 'title' => 'FIFA 24', 'platform' => 'PS5', 'genre' => 'Sports', 'stok' => 5, 'harga_per_hari' => 20000],
            ['judul' => 'God of War', 'title' => 'God of War', 'platform' => 'PS4', 'genre' => 'Action', 'stok' => 4, 'harga_per_hari' => 18000],
            ['judul' => 'The Last of Us', 'title' => 'The Last of Us', 'platform' => 'PS3', 'genre' => 'Adventure', 'stok' => 3, 'harga_per_hari' => 15000],
        ];
        foreach ($games as $g) {
            Game::updateOrCreate(
                ['title' => $g['title'], 'platform' => $g['platform']],
                $g
            );
        }

        // Seed Accessories
        $accessories = [
            ['nama' => 'DualSense Controller', 'name' => 'DualSense Controller', 'jenis' => 'Controller', 'type' => 'controller', 'stok' => 5, 'harga_per_hari' => 10000],
            ['nama' => 'Headset PS', 'name' => 'PS Headset', 'jenis' => 'Headset', 'type' => 'headset', 'stok' => 3, 'harga_per_hari' => 12000],
        ];
        foreach ($accessories as $a) {
            Accessory::updateOrCreate(
                ['name' => $a['name']],
                $a
            );
        }

        // Sample Rentals for demo flows (riwayat & pengembalian)
        $pelanggan = User::where('email', 'pelanggan@gmail.com')->first();
        $kasir = User::where('email', 'kasir@gmail.com')->first();

        if ($pelanggan) {
            $ps4 = UnitPS::where('model', 'PS4')->first();
            $fifa = Game::where('title', 'FIFA 24')->first();

            // 1) Rental selesai (returned)
            if ($ps4 && $fifa) {
                $start = Carbon::now()->subDays(2)->setTime(10,0);
                $due = Carbon::now()->subDays(1)->setTime(10,0);
                $hours = $start->diffInHours($due);
                $subtotal = ($ps4->price_per_hour * 1 * $hours) + ($fifa->harga_per_hari * 1); // 1 hari game

                $r1 = Rental::firstOrCreate(
                    ['kode' => 'DEMO1'],
                    [
                        'user_id' => $pelanggan->id,
                        'handled_by' => optional($kasir)->id,
                        'start_at' => $start,
                        'due_at' => $due,
                        'returned_at' => $due,
                        'status' => 'returned',
                        'subtotal' => $subtotal,
                        'discount' => 0,
                        'total' => $subtotal,
                        'paid' => 1,
                        'notes' => 'Demo transaksi selesai',
                    ]
                );

                if ($r1->wasRecentlyCreated) {
                    RentalItem::create([
                        'rental_id' => $r1->id,
                        'rentable_type' => UnitPS::class,
                        'rentable_id' => $ps4->id,
                        'quantity' => 1,
                        'price' => $ps4->price_per_hour,
                        'total' => $ps4->price_per_hour * $hours,
                    ]);
                    RentalItem::create([
                        'rental_id' => $r1->id,
                        'rentable_type' => Game::class,
                        'rentable_id' => $fifa->id,
                        'quantity' => 1,
                        'price' => $fifa->harga_per_hari,
                        'total' => $fifa->harga_per_hari,
                    ]);
                }
            }

            // 2) Rental aktif/menunggu (pending)
            $ps5 = UnitPS::where('model', 'PS5')->first();
            if ($ps5) {
                $start = Carbon::now()->subHours(3);
                $due = Carbon::now()->addHours(21); // total 24 jam
                $hours = $start->diffInHours($due);
                $subtotal = $ps5->price_per_hour * $hours;

                $r2 = Rental::firstOrCreate(
                    ['kode' => 'DEMO2'],
                    [
                        'user_id' => $pelanggan->id,
                        'handled_by' => optional($kasir)->id,
                        'start_at' => $start,
                        'due_at' => $due,
                        'status' => 'pending',
                        'subtotal' => $subtotal,
                        'discount' => 0,
                        'total' => $subtotal,
                        'paid' => 0,
                        'notes' => 'Demo transaksi aktif',
                    ]
                );

                if ($r2->wasRecentlyCreated) {
                    RentalItem::create([
                        'rental_id' => $r2->id,
                        'rentable_type' => UnitPS::class,
                        'rentable_id' => $ps5->id,
                        'quantity' => 1,
                        'price' => $ps5->price_per_hour,
                        'total' => $ps5->price_per_hour * $hours,
                    ]);
                }
            }

            // Seed Keranjang Saya (agar langsung terlihat saat uji peminjaman)
            // Tambah 1 PS4 dan 1 Game FIFA 24 ke keranjang pelanggan jika belum ada
            if ($ps4) {
                Cart::firstOrCreate(
                    ['user_id' => $pelanggan->id, 'type' => 'unitps', 'item_id' => $ps4->id],
                    [
                        'name' => $ps4->nama ?? $ps4->name,
                        'price' => (float) ($ps4->harga_per_jam ?? $ps4->price_per_hour ?? 0),
                        'price_type' => 'per_jam',
                        'quantity' => 1,
                    ]
                );
            }
            if (isset($fifa) && $fifa) {
                Cart::firstOrCreate(
                    ['user_id' => $pelanggan->id, 'type' => 'game', 'item_id' => $fifa->id],
                    [
                        'name' => $fifa->judul ?? $fifa->title,
                        'price' => (float) ($fifa->harga_per_hari ?? $fifa->price_per_day ?? 0),
                        'price_type' => 'per_hari',
                        'quantity' => 1,
                    ]
                );
            }
        }
    }
}
