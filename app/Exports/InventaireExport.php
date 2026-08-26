<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventaireExport implements FromCollection, WithHeadings, WithMapping
{
    protected $categorie;
    protected $etat;

    public function __construct($categorie = null, $etat = null)
    {
        $this->categorie = $categorie;
        $this->etat = $etat;
    }

    public function collection(): Collection
    {
        return DB::table('inventaire')
            ->leftJoin(
                'categories_inventaire',
                'inventaire.id_categorie',
                '=',
                'categories_inventaire.id_categorie'
            )
            ->select(
                'inventaire.*',
                'categories_inventaire.libelle as categorie'
            )
            ->when(
                $this->categorie,
                function ($query) {
                    $query->where(
                        'inventaire.id_categorie',
                        $this->categorie
                    );
                }
            )
            ->when(
                $this->etat,
                function ($query) {
                    $query->where(
                        'inventaire.etat',
                        $this->etat
                    );
                }
            )
            ->orderBy('designation')
            ->get();
    }

    public function headings(): array
    {
        return [
            'N°',
            'Désignation',
            'Catégorie',
            'Quantité',
            'Date d’acquisition',
            'État',
            'Localisation',
            'Responsable',
            'Observation',
        ];
    }

    public function map($bien): array
    {
        static $numero = 0;

        $numero++;

        return [
            $numero,
            $bien->designation,
            $bien->categorie ?? '',
            $bien->quantite,
            $bien->date_acquisition,
            $bien->etat,
            $bien->localisation,
            $bien->responsable,
            $bien->observation,
        ];
    }
}