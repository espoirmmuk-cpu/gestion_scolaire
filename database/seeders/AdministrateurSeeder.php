<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministrateurSeeder extends Seeder
{
    public function run(): void
    {
        $administrateur = User::updateOrCreate(
            [
                'email' => 'admin@gestion-scolaire.local',
            ],
            [
                'nom' => 'Administrateur',
                'mot_de_passe' => Hash::make('Admin@12345'),
                'statut' => 'ACTIF',
            ]
        );

        $administrateur->roles()->syncWithoutDetaching([1]);
    }
}