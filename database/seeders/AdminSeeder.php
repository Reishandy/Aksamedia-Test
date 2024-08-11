<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Not secure, but this is just a test so y'know...

        Admin::create([
            'name' => 'Admin',
            'username' => 'admin',
            'phone' => '081234567890',
            'email' => 'admin@admin.com',
            'password' => Hash::make('pastibisa'),
        ]);

        Admin::create([
            'name' => 'Reishandy',
            'username' => 'rei',
            'phone' => '089685440717',
            'email' => 'akbar@reishandy.my.id',
            'password' => Hash::make('pastibisa'),
        ]);
    }
}
