<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'ridwan')->first();
        Contact::create([
            'first_name' => 'Ridwan',
            'last_name' => 'Hidayat',
            'email' => 'ridwan.nurulhidayat@gmail.com',
            'phone' => '+6283141418173',
            'user_id' => $user->id
        ]);
    }
}
