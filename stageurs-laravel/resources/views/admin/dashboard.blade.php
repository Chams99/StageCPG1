@extends('layouts.app')

@section('title', 'Admin Dashboard - StageursApp')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Admin Dashboard
    </h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ $stats['total_students'] }}</div>
                    <div class="label">Total Students</div>
                </div>
                <i class="fas fa-users" style="font-size: 2rem; opacity: 0.7;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ $stats['total_faculties'] }}</div>
                    <div class="label">Faculties</div>
                </div>
                <i class="fas fa-university" style="font-size: 2rem; opacity: 0.7;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ $stats['total_sections'] }}</div>
                    <div class="label">Sections</div>
                </div>
                <i class="fas fa-layer-group" style="font-size: 2rem; opacity: 0.7;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ $stats['total_supervisors'] }}</div>
                    <div class="label">Supervisors</div>
                </div>
                <i class="fas fa-user-tie" style="font-size: 2rem; opacity: 0.7;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Badge Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-id-card me-2"></i>
                    Badge Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h3 class="text-success">{{ $stats['students_with_badges'] }}</h3>
                            <p class="text-muted mb-0">With Badges</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-warning">{{ $stats['students_without_badges'] }}</h3>
                        <p class="text-muted mb-0">Without Badges</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('etudiants.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add New Student
                    </a>
                    <form method="POST" action="{{ route('admin.generate.missing.badges') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-id-card me-2"></i>
                            Generate Missing Badges
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.generate.all.badges') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-sync me-2"></i>
                            Regenerate All Badges
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Students -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-clock me-2"></i>
            Recent Students
        </h5>
        <a href="{{ route('etudiants.index') }}" class="btn btn-sm btn-primary">
            View All
        </a>
    </div>
    <div class="card-body">
        @if($recent_students->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Faculty</th>
                            <th>Section</th>
                            <th>Supervisor</th>
                            <th>Badge</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_students as $student)
                            <tr>
                                <td>
                                    <strong>{{ $student->full_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $student->email }}</small>
                                </td>
                                <td>{{ $student->identification_card_number }}</td>
                                <td>{{ $student->section?->faculty?->nom ?? 'N/A' }}</td>
                                <td>{{ $student->section?->nom ?? 'N/A' }}</td>
                                <td>{{ $student->encadreur?->full_name ?? 'N/A' }}</td>
                                <td>
                                    @if($student->badge_path)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Generated
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation me-1"></i>Missing
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('etudiants.show', $student) }}" 
                                           class="btn btn-outline-primary" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('etudiants.edit', $student) }}" 
                                           class="btn btn-outline-secondary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($student->badge_path)
                                            <a href="{{ route('etudiants.download.badge', $student) }}" 
                                               class="btn btn-outline-success" 
                                               title="Download Badge">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-users" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted mt-2">No students found. Add your first student to get started.</p>
                <a href="{{ route('etudiants.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add Student
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
