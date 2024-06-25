<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'nisn' => '1234567890',
            'class' => 'XII RPL',
            'date_of_birth' => '2001-02-22',
            'address' => 'Jl. Banjarmasin No. 12',
            'phone_number' => '081234567890',
            'image' => 'default.jpg',
            'role' => 'admin',
        ]);
    }
}
