<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PalmaresExport implements FromCollection, WithHeadings, WithMapping
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Données du palmarès.
     */
    public function collection(): Enumerable
    {
        return DB::table('bulletins')
            ->join(
                'eleves',
                'bulletins.id_eleve',
                '=',
                'eleves.id_eleve'
            )
            ->where(
                'bulletins.id_annee_scolaire',
                $this->request->annee
            )
            ->where(
                'bulletins.id_periode',
                $this->request->periode
            )
            ->where(
                'bulletins.id_classe',
                $this->request->classe
            )
            ->select(
                'bulletins.rang',
                'eleves.matricule',
                'eleves.nom',
                'eleves.postnom',
                'eleves.prenom',
                'eleves.sexe',
                'bulletins.moyenne',
                'bulletins.pourcentage',
                'bulletins.decision'
            )
            ->orderByRaw(
                'CASE
                    WHEN bulletins.rang IS NULL
                    THEN 1
                    ELSE 0
                 END'
            )
            ->orderBy('bulletins.rang')
            ->orderByDesc('bulletins.moyenne')
            ->get();
    }

    /**
     * En-têtes Excel.
     */
    public function headings(): array
    {
        return [
            'Rang',
            'Matricule',
            'Nom',
            'Postnom',
            'Prénom',
            'Sexe',
            'Moyenne',
            'Pourcentage',
            'Décision',
        ];
    }

    /**
     * Format d'une ligne Excel.
     */
    public function map($eleve): array
    {
        return [
            $eleve->rang,
            $eleve->matricule,
            $eleve->nom,
            $eleve->postnom,
            $eleve->prenom,
            $eleve->sexe,
            $eleve->moyenne,
            $eleve->pourcentage,
            $eleve->decision,
        ];
    }
}