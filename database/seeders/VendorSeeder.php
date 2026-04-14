<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Seed the application's vendor data.
     */
    public function run(): void
    {
        $vendors = [
            'Kopi Nusantara',
            'Ayam Geprek Juara',
            'Bakso Legend',
        ];

        foreach ($vendors as $namaVendor) {
            Vendor::updateOrCreate(
                ['nama_vendor' => $namaVendor],
                ['nama_vendor' => $namaVendor]
            );
        }
    }
}
