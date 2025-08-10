<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    /**
     * Get the sections for this faculty
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
