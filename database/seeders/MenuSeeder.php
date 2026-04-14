<?php

namespace Database\Seeders;

use App\Models\menu;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Seed the application's menu data.
     */
    public function run(): void
    {
        $menusByVendor = [
            'Kopi Nusantara' => [
                ['nama_menu' => 'Americano', 'harga' => 18000, 'path_gambar' => 'menu/americano.jpg'],
                ['nama_menu' => 'Latte', 'harga' => 22000, 'path_gambar' => 'menu/latte.jpg'],
            ],
            'Ayam Geprek Juara' => [
                ['nama_menu' => 'Ayam Geprek Original', 'harga' => 20000, 'path_gambar' => 'menu/geprek-original.jpg'],
                ['nama_menu' => 'Ayam Geprek Keju', 'harga' => 24000, 'path_gambar' => 'menu/geprek-keju.jpg'],
            ],
            'Bakso Legend' => [
                ['nama_menu' => 'Bakso Urat', 'harga' => 17000, 'path_gambar' => 'menu/bakso-urat.jpg'],
                ['nama_menu' => 'Bakso Isi Telur', 'harga' => 21000, 'path_gambar' => 'menu/bakso-isi-telur.jpg'],
            ],
        ];

        foreach ($menusByVendor as $namaVendor => $menus) {
            $vendor = Vendor::where('nama_vendor', $namaVendor)->first();

            if (! $vendor) {
                continue;
            }

            foreach ($menus as $menuItem) {
                menu::updateOrCreate(
                    [
                        'id_vendor' => $vendor->id,
                        'nama_menu' => $menuItem['nama_menu'],
                    ],
                    [
                        'harga' => $menuItem['harga'],
                        'path_gambar' => $menuItem['path_gambar'],
                    ]
                );
            }
        }
    }
}
