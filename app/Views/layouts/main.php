<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - ระบบสโมสรนักศึกษา</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f4f6f9; 
            font-family: 'Sarabun', sans-serif; /* ใช้ฟอนต์ Sarabun */
        }
        .navbar-brand { font-weight: 600; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,.1); border: none; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>"><i class="fas fa-university"></i> Sci-Tech Club</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
    <?php if (session()->get('is_logged_in')): ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle"></i> คุณ<?= session()->get('user_name') ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= base_url('dashboard') ?>">หน้าหลัก</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">ออกจากระบบ</a></li>
            </ul>
        </li>
    <?php else: ?>
        <?php endif; ?>
</ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>

    <footer class="text-center mt-5 py-4 text-muted">
        <small>&copy; <?= date('Y') ?> คณะวิทยาศาสตร์และเทคโนโลยี มรภ.เพชรบูรณ์</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>