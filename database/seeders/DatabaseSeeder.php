<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// import the new seeder
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // make sure roles exist before creating users
        $this->call(RoleSeeder::class);

        // you can generate random users or a fixed one for development
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            // role_id will be null unless factory sets it; set default here
            'role_id' => \App\Models\Role::firstWhere('name','user')->id ?? null,
        ]);
    }
}
