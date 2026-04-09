<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AurofarmaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Users
        $pin = Hash::make('1234');
        $pwd = Hash::make('password');
        
        $operarioId = DB::table('users')->insertGetId(['name' => 'Juan Operario', 'email' => 'operario@aurofarma.com', 'password' => $pwd, 'role' => 'operario', 'pin_firma' => $pin]);
        $calidadId = DB::table('users')->insertGetId(['name' => 'Maria Calidad', 'email' => 'calidad@aurofarma.com', 'password' => $pwd, 'role' => 'calidad', 'pin_firma' => $pin]);
        $dirTecId = DB::table('users')->insertGetId(['name' => 'Dr. Carlos DT', 'email' => 'dt@aurofarma.com', 'password' => $pwd, 'role' => 'direccion_tecnica', 'pin_firma' => $pin]);
        
        // Bloque protegido para el usuario administrador
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@aurofarma.com'], // El correo fijo de acceso
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'), // Contraseña fija inalterable
                'role' => 'admin',
                'pin_firma' => Hash::make('1234')
            ]
        );
        $adminId = $admin->id;

        // 2. Equipments
        $mezcladoraId = DB::table('equipment')->insertGetId(['name' => 'Mezcladora de Polvos 500L', 'equipment_code' => 'MZ-500-01', 'next_calibration_date' => Carbon::now()->addMonths(6)->toDateString()]);
        $balanzaId = DB::table('equipment')->insertGetId(['name' => 'Balanza Analitica', 'equipment_code' => 'BAL-001', 'next_calibration_date' => Carbon::now()->addMonths(1)->toDateString()]);
        
        // Items and Formulas will be handled by their respective CSV Seeders from now on

    }
}
