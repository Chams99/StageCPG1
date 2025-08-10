<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'photo_path',
        'start_date',
        'end_date',
        'identification_card_number',
        'badge_path',
        'section_id',
        'encadreur_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the section that owns this student
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the encadreur that supervises this student
     */
    public function encadreur()
    {
        return $this->belongsTo(Encadreur::class);
    }

    /**
     * Get the faculty through the section
     */
    public function faculty()
    {
        return $this->hasOneThrough(Faculty::class, Section::class);
    }

    /**
     * Get the full name of the student
     */
    public function getFullNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }

    /**
     * Validation rules for creating a student
     */
    public static function rules($id = null)
    {
        return [
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'required|email',
            'telephone' => 'nullable|string',
            'identification_card_number' => [
                'required',
                'string',
                'size:8',
                'regex:/^\d{8}$/',
                Rule::unique('etudiants')->ignore($id)
            ],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'section_id' => 'nullable|exists:sections,id',
            'encadreur_id' => 'nullable|exists:encadreurs,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024'
        ];
    }

    /**
     * Custom validation messages
     */
    public static function messages()
    {
        return [
            'nom.required' => 'Last name is required',
            'nom.max' => 'Last name cannot exceed 50 characters',
            'prenom.required' => 'First name is required',
            'prenom.max' => 'First name cannot exceed 50 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'identification_card_number.required' => 'National ID card number is required',
            'identification_card_number.size' => 'National ID card number must be exactly 8 digits',
            'identification_card_number.regex' => 'National ID card number must be exactly 8 digits',
            'identification_card_number.unique' => 'This national ID card number is already registered',
            'end_date.after_or_equal' => 'End date must be after or equal to start date',
            'section_id.exists' => 'Selected section does not exist',
            'encadreur_id.exists' => 'Selected supervisor does not exist',
            'photo.image' => 'The file must be an image',
            'photo.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif',
            'photo.max' => 'The image may not be greater than 1MB'
        ];
    }
}
