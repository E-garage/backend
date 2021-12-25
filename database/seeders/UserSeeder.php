<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
    }

    private function createAdmin()
    {
        UserModel::factory()->create([
            'name' => 'admin',
            'email' => 'admin@egarage.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
            'role' => UserModel::ROLES['admin'],
        ]);
    }
}
