<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Cart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
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

        // Seed Unit PS
        $units = [
            [
                'name' => 'PS4 Slim', 
                'brand' => 'Sony', 
                'model' => 'PS4', 
                'serial_number' => 'PS4-1001', 
                'price_per_hour' => 15000, 
                'stock' => 3,
                'foto' => 'https://images.unsplash.com/photo-1507457379470-08b800bebc67?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'PS5 Standard', 
                'brand' => 'Sony', 
                'model' => 'PS5', 
                'serial_number' => 'PS5-2001', 
                'price_per_hour' => 25000, 
                'stock' => 2,
                'foto' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'PS3 Super Slim', 
                'brand' => 'Sony', 
                'model' => 'PS3', 
                'serial_number' => 'PS3-3001', 
                'price_per_hour' => 10000, 
                'stock' => 4,
                'foto' => 'https://images.unsplash.com/photo-1535654778628-61187a6743e6?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'PS4 Pro', 
                'brand' => 'Sony', 
                'model' => 'PS4', 
                'serial_number' => 'PS4-1002', 
                'price_per_hour' => 18000, 
                'stock' => 2,
                'foto' => 'https://images.unsplash.com/photo-1507457379470-08b800bebc67?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'PS5 Digital', 
                'brand' => 'Sony', 
                'model' => 'PS5', 
                'serial_number' => 'PS5-2002', 
                'price_per_hour' => 23000, 
                'stock' => 1,
                'foto' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=800&q=80'
            ],
        ];
        foreach ($units as $u) {
            // Map English fields to Indonesian fields for consistency
            $u['stok'] = $u['stock'];
            $u['kondisi'] = 'baik';
            $u['harga_per_jam'] = $u['price_per_hour'];
            $u['nomor_seri'] = $u['serial_number'];
            $u['merek'] = $u['brand'];
            $u['nama'] = $u['name'];
            
            UnitPS::updateOrCreate(
                ['serial_number' => $u['serial_number']],
                $u
            );
        }

        // Seed Games - PERBAIKAN: gunakan 'judul' bukan 'title'
        $games = [
            ['judul' => 'FIFA 24', 'platform' => 'PS5', 'genre' => 'Sports', 'stok' => 5, 'harga_per_hari' => 20000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'God of War Ragnarok', 'platform' => 'PS5', 'genre' => 'Action', 'stok' => 4, 'harga_per_hari' => 25000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'The Last of Us Part II', 'platform' => 'PS4', 'genre' => 'Adventure', 'stok' => 3, 'harga_per_hari' => 18000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Spider-Man Miles Morales', 'platform' => 'PS5', 'genre' => 'Action', 'stok' => 6, 'harga_per_hari' => 22000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1624526267942-ab0ff8a3e972?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Uncharted 4', 'platform' => 'PS4', 'genre' => 'Adventure', 'stok' => 5, 'harga_per_hari' => 17000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Horizon Forbidden West', 'platform' => 'PS5', 'genre' => 'RPG', 'stok' => 4, 'harga_per_hari' => 23000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Gran Turismo 7', 'platform' => 'PS5', 'genre' => 'Racing', 'stok' => 3, 'harga_per_hari' => 21000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Resident Evil Village', 'platform' => 'PS4', 'genre' => 'Horror', 'stok' => 4, 'harga_per_hari' => 19000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1509248961158-e54f6934749c?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Ghost of Tsushima', 'platform' => 'PS4', 'genre' => 'Action', 'stok' => 5, 'harga_per_hari' => 20000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1535905557558-afc4877a26fc?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Ratchet & Clank', 'platform' => 'PS5', 'genre' => 'Adventure', 'stok' => 3, 'harga_per_hari' => 22000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1622979135225-d2ba269fb1bd?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Call of Duty MW3', 'platform' => 'PS5', 'genre' => 'Shooter', 'stok' => 6, 'harga_per_hari' => 24000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1560253023-3ec5d502959f?auto=format&fit=crop&w=800&q=80'],
            ['judul' => 'Assassins Creed Valhalla', 'platform' => 'PS4', 'genre' => 'RPG', 'stok' => 4, 'harga_per_hari' => 18000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=800&q=80'],
        ];
        foreach ($games as $g) {
            Game::updateOrCreate(
                ['judul' => $g['judul'], 'platform' => $g['platform']],
                $g
            );
        }

        // Seed Accessories - PERBAIKAN: gunakan 'nama' bukan 'name'
        $accessories = [
            ['nama' => 'DualSense Controller White', 'jenis' => 'Controller', 'stok' => 5, 'harga_per_hari' => 10000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'DualSense Controller Black', 'jenis' => 'Controller', 'stok' => 4, 'harga_per_hari' => 10000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'DualShock 4 Controller', 'jenis' => 'Controller', 'stok' => 6, 'harga_per_hari' => 8000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'PS5 Pulse 3D Headset', 'jenis' => 'Headset', 'stok' => 3, 'harga_per_hari' => 15000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1599481238640-4c1288750d7a?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'PS4 Gaming Headset', 'jenis' => 'Headset', 'stok' => 4, 'harga_per_hari' => 12000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1599481238640-4c1288750d7a?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'DualSense Charging Station', 'jenis' => 'Charger', 'stok' => 4, 'harga_per_hari' => 8000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1622979135225-d2ba269fb1bd?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'PS VR2 Headset', 'jenis' => 'VR', 'stok' => 2, 'harga_per_hari' => 35000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1622979135225-d2ba269fb1bd?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'PS VR (Gen 1)', 'jenis' => 'VR', 'stok' => 3, 'harga_per_hari' => 25000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1622979135225-d2ba269fb1bd?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'HD Camera', 'jenis' => 'Camera', 'stok' => 2, 'harga_per_hari' => 10000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'Media Remote', 'jenis' => 'Remote', 'stok' => 3, 'harga_per_hari' => 5000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'Racing Wheel', 'jenis' => 'Wheel', 'stok' => 2, 'harga_per_hari' => 30000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80'],
            ['nama' => 'Move Controllers (Pair)', 'jenis' => 'Controller', 'stok' => 3, 'harga_per_hari' => 12000, 'kondisi' => 'baik', 'gambar' => 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=800&q=80'],
        ];
        foreach ($accessories as $a) {
            Accessory::updateOrCreate(
                ['nama' => $a['nama'], 'jenis' => $a['jenis']],
                $a
            );
        }

        // Sample Rentals
        $pelanggan = User::where('email', 'pelanggan@gmail.com')->first();
        $kasir = User::where('email', 'kasir@gmail.com')->first();

        if ($pelanggan) {
            $ps4 = UnitPS::where('model', 'PS4')->first();
            $fifa = Game::where('judul', 'FIFA 24')->first();

            if ($ps4 && $fifa) {
                $start = Carbon::now()->subDays(2)->setTime(10,0);
                $due = Carbon::now()->subDays(1)->setTime(10,0);
                $hours = $start->diffInHours($due);
                $subtotal = ($ps4->price_per_hour * 1 * $hours) + ($fifa->harga_per_hari * 1);

                $r1 = Rental::firstOrCreate(
                    ['kode' => 'DEMO1'],
                    [
                        'user_id' => $pelanggan->id,
                        'handled_by' => optional($kasir)->id,
                        'start_at' => $start,
                        'due_at' => $due,
                        'returned_at' => $due,
                        'status' => 'selesai',
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
                $due = Carbon::now()->addHours(21);
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

            // Seed Keranjang
            if ($ps4) {
                Cart::firstOrCreate(
                    ['user_id' => $pelanggan->id, 'type' => 'unitps', 'item_id' => $ps4->id],
                    [
                        'name' => $ps4->name,
                        'price' => (float) $ps4->price_per_hour,
                        'price_type' => 'per_jam',
                        'quantity' => 1,
                    ]
                );
            }
            if ($fifa) {
                Cart::firstOrCreate(
                    ['user_id' => $pelanggan->id, 'type' => 'game', 'item_id' => $fifa->id],
                    [
                        'name' => $fifa->judul,
                        'price' => (float) $fifa->harga_per_hari,
                        'price_type' => 'per_hari',
                        'quantity' => 1,
                    ]
                );
            }
        }

        // Ensure images are updated with the latest URLs
        $this->call(UpdateImagesSeeder::class);
    }
}