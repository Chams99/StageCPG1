@extends('layouts.app')

@section('title', 'Welcome - StageursApp')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-graduation-cap" style="font-size: 4rem; color: #667eea;"></i>
                    </div>
                    
                    <h1 class="card-title mb-4">Welcome to StageursApp</h1>
                    <p class="card-text lead mb-4">
                        Professional Student Internship Management System
                    </p>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-users mb-2" style="font-size: 2rem; color: #667eea;"></i>
                                <h5>Student Management</h5>
                                <p class="text-muted">Complete CRUD operations for student records</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-id-card mb-2" style="font-size: 2rem; color: #667eea;"></i>
                                <h5>Badge Generation</h5>
                                <p class="text-muted">Automatic PDF badge generation with photos</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-university mb-2" style="font-size: 2rem; color: #667eea;"></i>
                                <h5>Academic Structure</h5>
                                <p class="text-muted">Manage faculties, sections, and supervisors</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Admin Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
