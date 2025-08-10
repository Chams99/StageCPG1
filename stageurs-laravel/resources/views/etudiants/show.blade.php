@extends('layouts.app')

@section('title', 'Student Details')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Student Details</h4>
                    <div class="btn-group" role="group">
                        <a href="{{ route('etudiants.edit', $etudiant) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('etudiants.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($etudiant->photo_path)
                                <img src="{{ asset('storage/' . $etudiant->photo_path) }}" 
                                     alt="Student Photo" 
                                     class="img-fluid rounded mb-3" 
                                     style="max-width: 200px;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center mb-3" 
                                     style="width: 200px; height: 200px; margin: 0 auto;">
                                    <i class="fas fa-user fa-4x text-white"></i>
                                </div>
                            @endif
                            
                            @if($etudiant->badge_path)
                                <div class="mt-3">
                                    <a href="{{ route('etudiants.preview.badge', $etudiant) }}" 
                                       class="btn btn-outline-primary btn-sm me-2">
                                        <i class="fas fa-eye"></i> Preview Badge
                                    </a>
                                    <a href="{{ route('etudiants.download.badge', $etudiant) }}" 
                                       class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-download"></i> Download Badge
                                    </a>
                                </div>
                            @else
                                <div class="mt-3">
                                    <span class="badge bg-warning">No Badge Generated</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Full Name:</strong></div>
                                <div class="col-sm-8">{{ $etudiant->nom }} {{ $etudiant->prenom }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>ID Card Number:</strong></div>
                                <div class="col-sm-8">{{ $etudiant->numero_carte_identite }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Date of Birth:</strong></div>
                                <div class="col-sm-8">{{ $etudiant->date_naissance ? \Carbon\Carbon::parse($etudiant->date_naissance)->format('F j, Y') : 'Not specified' }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Email:</strong></div>
                                <div class="col-sm-8">
                                    <a href="mailto:{{ $etudiant->email }}">{{ $etudiant->email }}</a>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Phone:</strong></div>
                                <div class="col-sm-8">
                                    <a href="tel:{{ $etudiant->telephone }}">{{ $etudiant->telephone }}</a>
                                </div>
                            </div>
                            
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Academic Information</h5>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Faculty:</strong></div>
                                <div class="col-sm-8">
                                    @if($etudiant->section && $etudiant->section->faculty)
                                        {{ $etudiant->section->faculty->nom }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Section:</strong></div>
                                <div class="col-sm-8">
                                    @if($etudiant->section)
                                        {{ $etudiant->section->nom }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Supervisor:</strong></div>
                                <div class="col-sm-8">
                                    @if($etudiant->encadreur)
                                        <div>{{ $etudiant->encadreur->nom }} {{ $etudiant->encadreur->prenom }}</div>
                                        <small class="text-muted">{{ $etudiant->encadreur->fonction }}</small>
                                        <div><small><a href="mailto:{{ $etudiant->encadreur->email }}">{{ $etudiant->encadreur->email }}</a></small></div>
                                        <div><small><a href="tel:{{ $etudiant->encadreur->telephone }}">{{ $etudiant->encadreur->telephone }}</a></small></div>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </div>
                            </div>
                            
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Internship Information</h5>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Start Date:</strong></div>
                                <div class="col-sm-8">
                                    {{ $etudiant->date_debut_stage ? \Carbon\Carbon::parse($etudiant->date_debut_stage)->format('F j, Y') : 'Not specified' }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>End Date:</strong></div>
                                <div class="col-sm-8">
                                    {{ $etudiant->date_fin_stage ? \Carbon\Carbon::parse($etudiant->date_fin_stage)->format('F j, Y') : 'Not specified' }}
                                </div>
                            </div>
                            
                            @if($etudiant->date_debut_stage && $etudiant->date_fin_stage)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Duration:</strong></div>
                                    <div class="col-sm-8">
                                        @php
                                            $start = \Carbon\Carbon::parse($etudiant->date_debut_stage);
                                            $end = \Carbon\Carbon::parse($etudiant->date_fin_stage);
                                            $duration = $start->diffInDays($end);
                                        @endphp
                                        {{ $duration }} days
                                    </div>
                                </div>
                            @endif
                            
                            <h5 class="border-bottom pb-2 mb-3 mt-4">System Information</h5>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Created:</strong></div>
                                <div class="col-sm-8">{{ $etudiant->created_at->format('F j, Y \a\t g:i A') }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Last Updated:</strong></div>
                                <div class="col-sm-8">{{ $etudiant->updated_at->format('F j, Y \a\t g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
