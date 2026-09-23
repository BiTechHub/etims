<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HostelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Hostel Admin',
            'email' => 'hostel@gmail.com',
            'password' => Hash::make('12345678'),
            'role'=>'hostel_admin',
            'user_name'=>'hostel'
        ]);
    }
}
