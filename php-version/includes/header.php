<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>StageursApp</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/site.css" />
    <link rel="stylesheet" href="assets/css/buttons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="d-flex flex-column min-vh-100">
    <header>
        <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light bg-white border-bottom box-shadow mb-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">StageursApp</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".navbar-collapse" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse d-sm-inline-flex justify-content-between">
                    <ul class="navbar-nav flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link text-dark <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" 
                               href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark <?php echo (strpos($_SERVER['PHP_SELF'], 'admin/dashboard.php') !== false) ? 'active' : ''; ?>" 
                               href="admin/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark <?php echo (strpos($_SERVER['PHP_SELF'], 'admin/faculties.php') !== false) ? 'active' : ''; ?>" 
                               href="admin/faculties.php">Faculties</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark <?php echo (strpos($_SERVER['PHP_SELF'], 'admin/sections.php') !== false) ? 'active' : ''; ?>" 
                               href="admin/sections.php">Sections</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark <?php echo (strpos($_SERVER['PHP_SELF'], 'admin/encadreurs.php') !== false) ? 'active' : ''; ?>" 
                               href="admin/encadreurs.php">Encadreurs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark <?php echo (strpos($_SERVER['PHP_SELF'], 'students.php') !== false) ? 'active' : ''; ?>" 
                               href="students.php">Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark <?php echo (strpos($_SERVER['PHP_SELF'], 'privacy.php') !== false) ? 'active' : ''; ?>" 
                               href="privacy.php">Privacy</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <?php if (isLoggedIn()): ?>
                            <li class="nav-item">
                                <span class="navbar-text me-3">
                                    <i class="fas fa-user me-1"></i>Welcome, <?php echo $_SESSION['admin_username']; ?>!
                                </span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="logout.php">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="login.php">
                                    <i class="fas fa-sign-in-alt me-1"></i>Admin Login
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="flex-grow-1">
        <div class="container">
            <div class="pb-3"> 