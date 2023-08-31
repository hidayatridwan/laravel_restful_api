<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'ridwan',
            'password' => Hash::make('4377'),
            'name' => 'Ridwan Nurul Hidayat',
            'token' => '12345'
        ]);

        User::create([
            'username' => 'ridwan2',
            'password' => Hash::make('43772'),
            'name' => 'Ridwan Nurul Hidayat2',
            'token' => '123452'
        ]);
    }
}
