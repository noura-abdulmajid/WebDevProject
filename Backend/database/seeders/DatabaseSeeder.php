<?php

namespace Database\Seeders;

use App\Models\AdminUsers;
use App\Models\Customer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        AdminUsers::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin_password'),
        ]);

        $this->call(ProductSeeder::class);
        $this->call(CartSeeder::class);

    }
}
