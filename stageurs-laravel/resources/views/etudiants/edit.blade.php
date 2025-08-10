@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Student: {{ $etudiant->nom }} {{ $etudiant->prenom }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('etudiants.update', $etudiant) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom', $etudiant->nom) }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prenom" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom', $etudiant->prenom) }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_carte_identite" class="form-label">ID Card Number *</label>
                                    <input type="text" class="form-control @error('numero_carte_identite') is-invalid @enderror" 
                                           id="numero_carte_identite" name="numero_carte_identite" 
                                           value="{{ old('numero_carte_identite', $etudiant->numero_carte_identite) }}" 
                                           pattern="[0-9]{8}" maxlength="8" required>
                                    <div class="form-text">8 digits only</div>
                                    @error('numero_carte_identite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date of Birth *</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" name="date_naissance" 
                                           value="{{ old('date_naissance', $etudiant->date_naissance) }}" required>
                                    @error('date_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $etudiant->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Phone *</label>
                                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" name="telephone" value="{{ old('telephone', $etudiant->telephone) }}" required>
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section_id" class="form-label">Section *</label>
                                    <select class="form-select @error('section_id') is-invalid @enderror" 
                                            id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" 
                                                    {{ old('section_id', $etudiant->section_id) == $section->id ? 'selected' : '' }}>
                                                {{ $section->nom }} ({{ $section->faculty->nom }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="encadreur_id" class="form-label">Supervisor *</label>
                                    <select class="form-select @error('encadreur_id') is-invalid @enderror" 
                                            id="encadreur_id" name="encadreur_id" required>
                                        <option value="">Select Supervisor</option>
                                        @foreach($encadreurs as $encadreur)
                                            <option value="{{ $encadreur->id }}" 
                                                    {{ old('encadreur_id', $etudiant->encadreur_id) == $encadreur->id ? 'selected' : '' }}>
                                                {{ $encadreur->nom }} {{ $encadreur->prenom }} - {{ $encadreur->fonction }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('encadreur_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_debut_stage" class="form-label">Internship Start Date *</label>
                                    <input type="date" class="form-control @error('date_debut_stage') is-invalid @enderror" 
                                           id="date_debut_stage" name="date_debut_stage" 
                                           value="{{ old('date_debut_stage', $etudiant->date_debut_stage) }}" required>
                                    @error('date_debut_stage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_fin_stage" class="form-label">Internship End Date *</label>
                                    <input type="date" class="form-control @error('date_fin_stage') is-invalid @enderror" 
                                           id="date_fin_stage" name="date_fin_stage" 
                                           value="{{ old('date_fin_stage', $etudiant->date_fin_stage) }}" required>
                                    @error('date_fin_stage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            @if($etudiant->photo_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $etudiant->photo_path) }}" 
                                         alt="Current Photo" 
                                         class="img-thumbnail" 
                                         style="max-width: 150px;">
                                    <small class="d-block text-muted">Current photo</small>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" accept="image/*">
                            <div class="form-text">JPG, JPEG, PNG, GIF up to 1MB. Leave empty to keep current photo.</div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('etudiants.show', $etudiant) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Details
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
