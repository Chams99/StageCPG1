<?php
require_once '../config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('../login.php');
}

$page_title = "Admin Dashboard";

// Get statistics
$stats = [];

// Count students
$stmt = $pdo->query("SELECT COUNT(*) as count FROM etudiants");
$stats['students'] = $stmt->fetch()['count'];

// Count faculties
$stmt = $pdo->query("SELECT COUNT(*) as count FROM faculties");
$stats['faculties'] = $stmt->fetch()['count'];

// Count sections
$stmt = $pdo->query("SELECT COUNT(*) as count FROM sections");
$stats['sections'] = $stmt->fetch()['count'];

// Count encadreurs
$stmt = $pdo->query("SELECT COUNT(*) as count FROM encadreurs");
$stats['encadreurs'] = $stmt->fetch()['count'];

// Get recent students
$stmt = $pdo->query("
    SELECT e.*, s.nom as section_nom, f.nom as faculty_nom 
    FROM etudiants e 
    LEFT JOIN sections s ON e.section_id = s.id 
    LEFT JOIN faculties f ON s.faculty_id = f.id 
    ORDER BY e.id DESC LIMIT 5
");
$recent_students = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-4 text-primary mb-3">
            <i class="fas fa-tachometer-alt me-3"></i>Admin Dashboard
        </h1>
        <p class="lead text-muted">Overview of all system data and statistics</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['students']; ?></h4>
                        <p class="mb-0">Students</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['faculties']; ?></h4>
                        <p class="mb-0">Faculties</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-university fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['sections']; ?></h4>
                        <p class="mb-0">Sections</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-layer-group fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['encadreurs']; ?></h4>
                        <p class="mb-0">Supervisors</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="../students/create.php" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Add Student
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="faculties.php" class="btn btn-success w-100">
                            <i class="fas fa-university me-2"></i>Manage Faculties
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="sections.php" class="btn btn-info w-100">
                            <i class="fas fa-layer-group me-2"></i>Manage Sections
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="encadreurs.php" class="btn btn-warning w-100">
                            <i class="fas fa-user-tie me-2"></i>Manage Supervisors
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Students -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Recent Students
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_students)): ?>
                    <p class="text-muted text-center">No students found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Section</th>
                                    <th>Faculty</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_students as $student): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo sanitize($student['nom'] . ' ' . $student['prenom']); ?></strong>
                                        </td>
                                        <td><?php echo sanitize($student['email']); ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo sanitize($student['section_nom'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo sanitize($student['faculty_nom'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <a href="../students/view.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="../students.php" class="btn btn-outline-info">
                            <i class="fas fa-list me-2"></i>View All Students
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 