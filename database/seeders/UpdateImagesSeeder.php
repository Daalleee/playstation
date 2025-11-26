<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;

class UpdateImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Update Unit PS Images
        $units = [
            'PS4-1001' => 'https://images.unsplash.com/photo-1507457379470-08b800bebc67?auto=format&fit=crop&w=800&q=80', // PS4 Slim
            'PS5-2001' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=800&q=80', // PS5 Standard
            'PS3-3001' => 'https://images.unsplash.com/photo-1535654778628-61187a6743e6?auto=format&fit=crop&w=800&q=80', // PS3
            'PS4-1002' => 'https://images.unsplash.com/photo-1507457379470-08b800bebc67?auto=format&fit=crop&w=800&q=80', // PS4 Pro
            'PS5-2002' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=800&q=80', // PS5 Digital
        ];

        foreach ($units as $serial => $url) {
            // Try exact match first, then loose match on model/name if serial fails
            $updated = UnitPS::where('serial_number', $serial)->update(['foto' => $url]);
            if ($updated === 0) {
                // Fallback for PS3 if serial doesn't match
                if (str_contains($serial, 'PS3')) {
                    UnitPS::where('model', 'like', '%PS3%')->orWhere('name', 'like', '%PS3%')->update(['foto' => $url]);
                }
            }
        }

        // Explicitly update PS3 Super Slim if it exists (handling potential naming variations)
        UnitPS::where('name', 'like', '%PS3 Super Slim%')->update([
            'foto' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQclAoswuAXma-y1k4IoBn29_V8-vghdPOLBg&s'
        ]);

        // Update Game Images
        $games = [
            'FIFA 24' => 'https://cdn0-production-images-kly.akamaized.net/pN6yoTedjd7w_xD98gm4mkCV-Dk=/1200x675/smart/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/4499366/original/052160900_1689134239-F0qQeTwXoAEaXq9.jpg',
            'God of War Ragnarok' => 'https://upload.wikimedia.org/wikipedia/id/e/ee/God_of_War_Ragnar%C3%B6k_cover.jpg',
            'The Last of Us Part II' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRufhIaMLRzejpHPPvx_Ot-ndxJj2DReOl9Q&s',
            'Spider-Man Miles Morales' => 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcT0dN7wpacn9NjKghbGwECsIxpIS8aVUNYamfGHtHGwlZOOzohQFShOIZ6zMHpp9d5pfm9peD10CchUCIDej3SdDU7KUp_sWfCEqFJgMKADrFaUDjM4EhA1',
            'Uncharted 4' => 'https://upload.wikimedia.org/wikipedia/en/thumb/1/1a/Uncharted_4_box_artwork.jpg/250px-Uncharted_4_box_artwork.jpg',
            'Horizon Forbidden West' => 'https://i.ytimg.com/vi_webp/qIyz_7Hz3U4/maxresdefault.webp',
            'Gran Turismo 7' => 'https://upload.wikimedia.org/wikipedia/id/thumb/1/14/Gran_Turismo_7_cover_art.jpg/250px-Gran_Turismo_7_cover_art.jpg',
            'Resident Evil Village' => 'https://digicodes.net/wp-content/uploads/2020/03/jual-game-pc-resident-evil-2.jpg',
            'Ghost of Tsushima' => 'https://image.api.playstation.com/vulcan/ap/rnd/202010/0222/b3iB2zf2xHj9shC0XDTULxND.png',
            'Ratchet & Clank' => 'https://cdn1.epicgames.com/offer/046aeb7098b94ac3961dad6c5dbe68c0/EGS_RatchetClankRiftApart_InsomniacGames_S1_2560x1440-aea43afcad407b14673456322e63a01b',
            'Call of Duty MW3' => 'https://upload.wikimedia.org/wikipedia/en/thumb/8/87/Call_of_Duty_Infinite_Warfare_cover.jpg/250px-Call_of_Duty_Infinite_Warfare_cover.jpg',
            'Assassins Creed Valhalla' => 'https://static.wixstatic.com/media/cc2968_c7dd965a9ef049ec97877ac3d6f2372b~mv2.jpg/v1/fill/w_739,h_424,al_c,lg_1,q_80,enc_auto/cc2968_c7dd965a9ef049ec97877ac3d6f2372b~mv2.jpg',
        ];

        foreach ($games as $title => $url) {
            Game::where('judul', 'like', '%' . $title . '%')->update(['gambar' => $url]);
        }

        // Update Accessory Images
        $accessories = [
            'DualSense' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwxxvgs4i0cD8b5GoCBe5OoFkDh7FzoRjTmQ&s',
            'DualShock' => 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=800&q=80',
            'Headset' => 'https://www.maxgaming.com/bilder/artiklar/15936.jpg?m=1588925516',
            'Charging' => 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=800&q=80',
            'PS VR2' => 'https://i.ytimg.com/vi_webp/-n_O8s6Eaps/maxresdefault.webp',
            'PS VR (Gen 1)' => 'https://upload.wikimedia.org/wikipedia/commons/8/81/Sony-PlayStation-4-PSVR-Headset-Mk1-FL.jpg',
            'Camera' => 'https://images.pexels.com/photos/274973/pexels-photo-274973.jpeg?cs=srgb&dl=pexels-pixabay-274973.jpg&fm=jpg',
            'Remote' => 'https://www.8bitdo.com/images/2021/media-remote-for-xbox/04-l.jpg?0428',
            'Wheel' => 'https://i.shgcdn.com/4da0c58d-e715-4684-9678-400d629803e7/-/format/auto/-/preview/3000x3000/-/quality/lighter/',
            'Move' => 'https://global.discourse-cdn.com/digikey/optimized/2X/f/ffb207f8590fbf32397d52e680193d5c99d5c0ee_2_1024x1024.jpeg',
        ];

        foreach ($accessories as $keyword => $url) {
            Accessory::where('nama', 'like', '%' . $keyword . '%')->update(['gambar' => $url]);
        }
    }
}
