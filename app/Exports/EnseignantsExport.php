<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EnseignantsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return DB::table('personnel')
            ->whereIn('fonction', [
                'ENSEIGNANT',
                'Enseignant',
                'enseignant',
            ])
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();
    }

    public function headings(): array
    {
        return [
            'N°',
            'Matricule',
            'Nom',
            'Postnom',
            'Prénom',
            'Sexe',
            'Fonction',
            'Qualification',
            'Téléphone',
            'Email',
            'Adresse',
            'Date d’engagement',
            'Statut',
        ];
    }

    public function map($enseignant): array
    {
        static $numero = 0;

        $numero++;

        return [
            $numero,
            $enseignant->matricule,
            $enseignant->nom,
            $enseignant->postnom,
            $enseignant->prenom,
            $enseignant->sexe,
            $enseignant->fonction,
            $enseignant->qualification,
            $enseignant->telephone,
            $enseignant->email,
            $enseignant->adresse,
            $enseignant->date_engagement,
            $enseignant->statut,
        ];
    }
}