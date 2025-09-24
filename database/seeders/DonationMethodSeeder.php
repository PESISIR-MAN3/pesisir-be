<?php

namespace Database\Seeders;

use App\Models\DonationMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DonationMethod::updateOrCreate(
            ['method_name' => 'BSI'],
            [
                'account_number' => '7327281881',
                'owner_name'     => 'Arum Sekar Waradanti',
            ]
        );

        DonationMethod::updateOrCreate(
            ['method_name' => 'BNI'],
            [
                'account_number' => '1980326177',
                'owner_name'     => 'Nirwasita Indrani',
            ]
        );
    }
}
