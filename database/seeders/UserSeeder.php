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
        $this->createVerifiedUsers(2);
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
            'role' => UserModel::ADMIN,
        ]);
    }

    private function createVerifiedUsers(int $amount = 0)
    {
        for($i = 0; $i < $amount; $i++) {
            UserModel::factory()->create([
                'name' => 'User' . $i,
                'email' => 'user' . $i . '@egarage.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password' . $i),
                'created_at' => now(),
                'updated_at' => now(),
                'role' => UserModel::USER,
            ]);
        }
    }
}
