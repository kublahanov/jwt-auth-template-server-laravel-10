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
            'name' => 'Кондрашов Роман Викторович',
            'email' => '4progs@inbox.ru',
            'password' => '1234567',
        ]);

        User::factory(12)
            ->unverified()
            ->create()
        ;
    }
}
