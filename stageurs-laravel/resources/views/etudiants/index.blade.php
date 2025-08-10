@extends('layouts.app')

@section('title', 'Students Management')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Students Management</h4>
                    <a href="{{ route('etudiants.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Student
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($etudiants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>ID Card</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Section</th>
                                        <th>Supervisor</th>
                                        <th>Badge</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiants as $etudiant)
                                    <tr>
                                        <td>
                                            @if($etudiant->photo_path)
                                                <img src="{{ asset('storage/' . $etudiant->photo_path) }}" 
                                                     alt="Student Photo" 
                                                     class="rounded-circle" 
                                                     width="40" height="40">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $etudiant->nom }} {{ $etudiant->prenom }}</td>
                                        <td>{{ $etudiant->numero_carte_identite }}</td>
                                        <td>{{ $etudiant->email }}</td>
                                        <td>{{ $etudiant->telephone }}</td>
                                        <td>
                                            @if($etudiant->section)
                                                {{ $etudiant->section->nom }}
                                                <small class="text-muted d-block">{{ $etudiant->section->faculty->nom ?? '' }}</small>
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($etudiant->encadreur)
                                                {{ $etudiant->encadreur->nom }} {{ $etudiant->encadreur->prenom }}
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($etudiant->badge_path)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('etudiants.preview.badge', $etudiant) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Preview Badge">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('etudiants.download.badge', $etudiant) }}" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       title="Download Badge">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge bg-warning">No Badge</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('etudiants.show', $etudiant) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('etudiants.edit', $etudiant) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('etudiants.destroy', $etudiant) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this student?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $etudiants->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No students found</h5>
                            <p class="text-muted">Start by adding your first student.</p>
                            <a href="{{ route('etudiants.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Student
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
