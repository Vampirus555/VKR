<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     CategorySeeder::class,
        // ]);

        User::factory()->state([
            'email' => 'romvlad2002@gmail.ru',
            'password' => bcrypt('mospolypass'),
            'name' => 'Владислав',
            'surname' => 'Романов',
            'role_id' => 2,
        ])->create();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
