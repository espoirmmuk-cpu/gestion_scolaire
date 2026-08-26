<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $primaryKey = 'id_note';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_evaluation',
        'id_eleve',
        'note',
        'appreciation',
    ];

    protected $casts = [
        'note' => 'decimal:2',
    ];

    /**
     * Évaluation
     */
    public function evaluation()
    {
        return $this->belongsTo(
            Evaluation::class,
            'id_evaluation',
            'id_evaluation'
        );
    }

    /**
     * Élève
     */
    public function eleve()
    {
        return $this->belongsTo(
            Eleve::class,
            'id_eleve',
            'id_eleve'
        );
    }
}