<?php
require_once 'config.php';
$page_title = "Home Page";
include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body text-center py-5">
                <h1 class="display-3 fw-bold mb-4">
                    <i class="fas fa-graduation-cap me-3"></i>Welcome to StageursApp
                </h1>
                <p class="lead mb-4">Comprehensive student internship management platform</p>
                <div class="d-flex justify-content-center gap-3">
                    <a class="btn btn-light btn-lg" href="students.php">
                        <i class="fas fa-users me-2"></i>View Students
                    </a>
                    <a class="btn btn-outline-light btn-lg" href="admin/dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="row mb-5">
    <div class="col-12">
        <h2 class="text-center mb-4">Platform Features</h2>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-user-graduate text-primary" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Student Management</h5>
                <p class="card-text">Easily manage student records, track internships, and monitor progress.</p>
                <a href="students.php" class="btn btn-outline-primary">
                    <i class="fas fa-users me-2"></i>Manage Students
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-user-tie text-warning" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Supervisor Management</h5>
                <p class="card-text">Manage supervisors (encadreurs) and assign them to students.</p>
                <a href="admin/encadreurs.php" class="btn btn-outline-warning">
                    <i class="fas fa-user-tie me-2"></i>Manage Supervisors
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-university text-success" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Academic Structure</h5>
                <p class="card-text">Organize faculties and sections for better academic management.</p>
                <a href="admin/faculties.php" class="btn btn-outline-success">
                    <i class="fas fa-university me-2"></i>Manage Faculties
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="students/create.php" class="btn btn-info w-100">
                            <i class="fas fa-user-plus me-2"></i>Add Student
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="admin/faculties.php" class="btn btn-outline-primary w-100">
                            <i class="fas fa-university me-2"></i>Manage Faculties
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="admin/sections.php" class="btn btn-outline-success w-100">
                            <i class="fas fa-layer-group me-2"></i>Manage Sections
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="admin/encadreurs.php" class="btn btn-outline-warning w-100">
                            <i class="fas fa-user-tie me-2"></i>Manage Supervisors
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-user-graduate text-info" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">Students</h4>
                            <p class="text-muted">Manage student records</p>
                            <a href="students.php" class="btn btn-sm btn-outline-info">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-user-tie text-warning" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">Supervisors</h4>
                            <p class="text-muted">Manage supervisors</p>
                            <a href="admin/encadreurs.php" class="btn btn-sm btn-outline-warning">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-university text-primary" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">Faculties</h4>
                            <p class="text-muted">Manage faculties</p>
                            <a href="admin/faculties.php" class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-layer-group text-success" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">Sections</h4>
                            <p class="text-muted">Manage sections</p>
                            <a href="admin/sections.php" class="btn btn-sm btn-outline-success">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 