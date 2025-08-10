@extends('layouts.app')

@section('title', 'Supervisors Management')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Supervisors Management</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEncadreurModal">
                        <i class="fas fa-plus"></i> Add Supervisor
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($encadreurs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Function</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Students Count</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($encadreurs as $encadreur)
                                    <tr>
                                        <td>{{ $encadreur->id }}</td>
                                        <td>{{ $encadreur->nom }} {{ $encadreur->prenom }}</td>
                                        <td>{{ $encadreur->fonction }}</td>
                                        <td>
                                            <a href="mailto:{{ $encadreur->email }}">{{ $encadreur->email }}</a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $encadreur->telephone }}">{{ $encadreur->telephone }}</a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $encadreur->etudiants_count }}</span>
                                        </td>
                                        <td>{{ $encadreur->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editEncadreurModal{{ $encadreur->id }}"
                                                        title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.encadreurs.delete', $encadreur) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this supervisor?')">
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
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No supervisors found</h5>
                            <p class="text-muted">Start by adding your first supervisor.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEncadreurModal">
                                <i class="fas fa-plus"></i> Add First Supervisor
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Supervisor Modal -->
<div class="modal fade" id="addEncadreurModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.encadreurs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Supervisor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fonction" class="form-label">Function/Position *</label>
                        <input type="text" class="form-control" id="fonction" name="fonction" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Phone *</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Supervisor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Supervisor Modals -->
@foreach($encadreurs as $encadreur)
<div class="modal fade" id="editEncadreurModal{{ $encadreur->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.encadreurs.update', $encadreur) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Supervisor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom{{ $encadreur->id }}" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="nom{{ $encadreur->id }}" name="nom" value="{{ $encadreur->nom }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom{{ $encadreur->id }}" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="prenom{{ $encadreur->id }}" name="prenom" value="{{ $encadreur->prenom }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fonction{{ $encadreur->id }}" class="form-label">Function/Position *</label>
                        <input type="text" class="form-control" id="fonction{{ $encadreur->id }}" name="fonction" value="{{ $encadreur->fonction }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email{{ $encadreur->id }}" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email{{ $encadreur->id }}" name="email" value="{{ $encadreur->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone{{ $encadreur->id }}" class="form-label">Phone *</label>
                        <input type="tel" class="form-control" id="telephone{{ $encadreur->id }}" name="telephone" value="{{ $encadreur->telephone }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Supervisor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
