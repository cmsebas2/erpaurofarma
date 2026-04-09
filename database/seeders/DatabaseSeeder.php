<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@aurofarma.com'], 
            [
                'name' => 'Administrador Aurofarma',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin'
            ]
        );

        $this->call([
            AurofarmaSeeder::class,
            ItemSeeder::class,
            // QMutinSeeder::class, // Comentado para iniciar con 0 productos
        ]);
    }
}
