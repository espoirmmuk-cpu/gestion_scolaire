<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamensNationauxExport implements FromCollection, WithHeadings, WithMapping
{
    protected $annee;

    public function __construct($annee = null)
    {
        $this->annee = $annee;
    }

    public function collection(): Collection
    {
        return DB::table('notes')
            ->join(
                'evaluations',
                'notes.id_evaluation',
                '=',
                'evaluations.id_evaluation'
            )
            ->join(
                'eleves',
                'notes.id_eleve',
                '=',
                'eleves.id_eleve'
            )
            ->join(
                'classes',
                'evaluations.id_classe',
                '=',
                'classes.id_classe'
            )
            ->join(
                'matieres',
                'evaluations.id_matiere',
                '=',
                'matieres.id_matiere'
            )
            ->where('evaluations.type_evaluation', 'Examen')
            ->when(
                $this->annee,
                function ($query) {
                    $query->where(
                        'evaluations.id_annee_scolaire',
                        $this->annee
                    );
                }
            )
            ->select(
                'eleves.matricule',
                'eleves.nom',
                'eleves.postnom',
                'eleves.prenom',
                'classes.libelle as classe',
                'matieres.libelle as matiere',
                'evaluations.libelle as examen',
                'evaluations.note_maximale',
                'notes.note',
                'notes.appreciation'
            )
            ->orderBy('classes.libelle')
            ->orderBy('eleves.nom')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Postnom',
            'Prénom',
            'Classe',
            'Matière',
            'Examen',
            'Note maximale',
            'Note obtenue',
            'Pourcentage',
            'Résultat',
            'Appréciation',
        ];
    }

    public function map($examen): array
    {
        $pourcentage = $examen->note_maximale > 0
            ? ($examen->note / $examen->note_maximale) * 100
            : 0;

        return [
            $examen->matricule,
            $examen->nom,
            $examen->postnom,
            $examen->prenom,
            $examen->classe,
            $examen->matiere,
            $examen->examen,
            $examen->note_maximale,
            $examen->note,
            round($pourcentage, 2) . ' %',
            $pourcentage >= 50 ? 'ADMIS' : 'ÉCHEC',
            $examen->appreciation,
        ];
    }
}