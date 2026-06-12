<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reparation extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'mecanicien_id',
        'date_reparation',
        'description',
        'cout',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_reparation' => 'date',
            'cout' => 'decimal:2',
        ];
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function mecanicien(): BelongsTo
    {
        return $this->belongsTo(Mecanicien::class);
    }
}
