<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'ridwan')->first();
        for ($i = 0; $i < 20; $i++) {
            Contact::create([
                'first_name' => 'test' . $i,
                'last_name' => 'test2' . $i,
                'email' => 'test@gmail.com' . $i,
                'phone' => '+6283141418173' . $i,
                'user_id' => $user->id
            ]);
        }
    }
}
