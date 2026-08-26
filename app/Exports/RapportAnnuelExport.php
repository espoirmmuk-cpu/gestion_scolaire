<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RapportAnnuelExport implements Export, FromCollection, WithHeadings
{
    /**
     * ID de l'année scolaire
     */
    protected $idAnnee;

    /**
     * Constructeur
     */
    public function __construct($idAnnee)
    {
        $this->idAnnee = $idAnnee;
    }

    /**
     * Données à exporter
     */
    public function collection(): Collection
    {
        return DB::table('inscriptions')

            ->join(
                'eleves',
                'eleves.id_eleve',
                '=',
                'inscriptions.id_eleve'
            )

            ->join(
                'classes',
                'classes.id_classe',
                '=',
                'inscriptions.id_classe'
            )

            ->where(
                'inscriptions.id_annee_scolaire',
                $this->idAnnee
            )

            ->select(

                'classes.libelle',

                'classes.option_classe',

                DB::raw("
                    SUM(
                        CASE
                            WHEN UPPER(TRIM(eleves.sexe)) = 'M'
                            THEN 1
                            ELSE 0
                        END
                    ) AS garcons
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN UPPER(TRIM(eleves.sexe)) = 'F'
                            THEN 1
                            ELSE 0
                        END
                    ) AS filles
                "),

                DB::raw("
                    COUNT(inscriptions.id_inscription) AS total
                ")

            )

            ->groupBy(
                'classes.id_classe',
                'classes.libelle',
                'classes.option_classe'
            )

            ->orderBy(
                'classes.libelle'
            )

            ->get();
    }

    /**
     * En-têtes du fichier Excel
     */
    public function headings(): array
    {
        return [

            'Classe',

            'Option',

            'Garçons',

            'Filles',

            'Total',

        ];
    }
}