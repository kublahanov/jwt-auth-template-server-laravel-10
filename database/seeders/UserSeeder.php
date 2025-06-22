<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    protected $admin = [
        'name' => 'Кондрашов Роман Викторович',
        'email' => '4progs@inbox.ru',
        'password' => '1234567',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exists = DB::table(User::getTableName())
            ->where('email', $this->admin['email'])
            ->exists()
        ;

        if (!$exists) {
            User::factory()->create($this->admin);

            User::factory(12)
                ->unverified()
                ->create()
            ;
        }
    }
}
