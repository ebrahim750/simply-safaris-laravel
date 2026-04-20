<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'ebrahim',
            'email' => 'projects@simplyit.co.tz',
            'password' => 'ebrahim750',
            'account_type' => 'root',
        ]);
    }
}
