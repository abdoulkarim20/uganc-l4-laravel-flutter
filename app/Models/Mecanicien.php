<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mecanicien extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'telephone', 'specialite'];

    public function reparations(): HasMany
    {
        return $this->hasMany(Reparation::class);
    }

    public function getNomCompletAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }
}
