<?php
require_once 'config.php';
$page_title = "Students";

// Get all students with related data
$stmt = $pdo->query("
    SELECT e.*, s.nom as section_nom, f.nom as faculty_nom, enc.nom as encadreur_nom 
    FROM etudiants e 
    LEFT JOIN sections s ON e.section_id = s.id 
    LEFT JOIN faculties f ON s.faculty_id = f.id 
    LEFT JOIN encadreurs enc ON e.encadreur_id = enc.id 
    ORDER BY e.nom, e.prenom
");
$students = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-4 text-primary">
                <i class="fas fa-user-graduate me-3"></i>Students
            </h1>
            <?php if (isLoggedIn()): ?>
                <a href="students/create.php" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Add Student
                </a>
            <?php endif; ?>
        </div>
        <p class="lead text-muted">Manage student records and internship information</p>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Student List (<?php echo count($students); ?>)
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($students)): ?>
            <div class="text-center py-5">
                <i class="fas fa-user-graduate text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No students found</h4>
                <p class="text-muted">Add your first student to get started</p>
                <?php if (isLoggedIn()): ?>
                    <a href="students/create.php" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Add First Student
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Section</th>
                            <th>Faculty</th>
                            <th>Supervisor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <strong><?php echo sanitize($student['nom'] . ' ' . $student['prenom']); ?></strong>
                                    <br>
                                    <small class="text-muted">ID: <?php echo $student['identification_card_number']; ?></small>
                                </td>
                                <td><?php echo sanitize($student['email']); ?></td>
                                <td><?php echo sanitize($student['telephone']); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo sanitize($student['section_nom'] ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo sanitize($student['faculty_nom'] ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-warning"><?php echo sanitize($student['encadreur_nom'] ?? 'Not Assigned'); ?></span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="students/view.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (isLoggedIn()): ?>
                                            <a href="students/edit.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="students/delete.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this student?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 