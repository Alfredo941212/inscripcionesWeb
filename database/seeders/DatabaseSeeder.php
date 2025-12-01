<?php

namespace Database\Seeders;

use App\Models\Discipline;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsers();
        $this->seedDisciplines();
    }

    private function seedUsers(): void
    {
        $users = [
            [
                'name' => 'Administrador General',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'password' => Hash::make('Admin123!'),
            ],
            [
                'name' => 'Supervisor General',
                'email' => 'supervisor@example.com',
                'role' => 'supervisor',
                'password' => Hash::make('Supervisor123!'),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }
    }

    private function seedDisciplines(): void
    {
        $disciplines = [
            ['name' => 'Fútbol 7 Femenil', 'category' => 'deportivo', 'gender' => 'femenil', 'max_capacity' => 16],
            ['name' => 'Fútbol 7 Varonil', 'category' => 'deportivo', 'gender' => 'varonil', 'max_capacity' => 16],
            ['name' => 'Soccer', 'category' => 'deportivo', 'gender' => 'mixto', 'max_capacity' => 16],
            ['name' => 'Básquetbol Femenil', 'category' => 'deportivo', 'gender' => 'femenil', 'max_capacity' => 12],
            ['name' => 'Básquetbol Varonil', 'category' => 'deportivo', 'gender' => 'varonil', 'max_capacity' => 12],
            ['name' => 'Voleibol Femenil', 'category' => 'deportivo', 'gender' => 'femenil', 'max_capacity' => 12],
            ['name' => 'Voleibol Varonil', 'category' => 'deportivo', 'gender' => 'varonil', 'max_capacity' => 12],
            ['name' => 'Atletismo Femenil', 'category' => 'deportivo', 'gender' => 'femenil', 'max_capacity' => 20],
            ['name' => 'Atletismo Varonil', 'category' => 'deportivo', 'gender' => 'varonil', 'max_capacity' => 20],
            ['name' => 'Ajedrez', 'category' => 'cultural', 'gender' => 'mixto', 'max_capacity' => 24],
            ['name' => 'Canto', 'category' => 'cultural', 'gender' => 'mixto', 'max_capacity' => 20],
            ['name' => 'Fotografía', 'category' => 'cultural', 'gender' => 'mixto', 'max_capacity' => 20],
        ];

        foreach ($disciplines as $data) {
            Discipline::updateOrCreate(['name' => $data['name']], $data);
        }
    }
}
