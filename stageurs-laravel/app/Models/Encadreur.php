<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encadreur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'fonction',
        'telephone'
    ];

    /**
     * Get the students supervised by this encadreur
     */
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }

    /**
     * Get the full name of the encadreur
     */
    public function getFullNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }
}
