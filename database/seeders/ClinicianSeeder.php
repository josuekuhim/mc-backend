<?php

namespace Database\Seeders;

use App\Models\Clinician;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClinicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Clinician::create([
            'name' => 'Test Clinician',
            'email' => 'clinician@example.com',
            'password' => Hash::make('password123'),
        ]);

        // You can also use factory to create multiple records
        Clinician::factory()->count(1)->create();
    }
}