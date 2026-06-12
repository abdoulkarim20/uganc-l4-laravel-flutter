<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'immatriculation', 'marque', 'modele', 'annee'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function reparations(): HasMany
    {
        return $this->hasMany(Reparation::class);
    }
}
