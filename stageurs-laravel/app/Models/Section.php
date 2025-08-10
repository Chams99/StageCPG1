<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'faculty_id'];

    /**
     * Get the faculty that owns this section
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the students in this section
     */
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }
}
