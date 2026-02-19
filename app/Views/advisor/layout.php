<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?> - อาจารย์ที่ปรึกษา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-width: 250px; min-height: 100vh; background: #2c3e50; color: white; }
        .sidebar a { color: #bdc3c7; text-decoration: none; padding: 15px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #1a252f; color: white; border-left: 4px solid #e67e22; }
        .content { width: 100%; }
        .top-nav { background: white; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar">
        <div class="p-4 text-center"><h5>Advisor Panel</h5><hr></div>
        <a href="<?= base_url('advisor/dashboard') ?>"><i class="fas fa-home me-2"></i> หน้าหลัก</a>
        <a href="<?= base_url('advisor/check_activities') ?>"><i class="fas fa-check-square me-2"></i> 1. ตรวจสอบกิจกรรม</a>
        <a href="<?= base_url('advisor/reports') ?>"><i class="fas fa-file-alt me-2"></i> 2. รายงานสรุปผล</a>
        <a href="<?= base_url('logout') ?>" class="text-danger mt-5"><i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ</a>
    </nav>
    <div class="content">
        <div class="top-nav d-flex justify-content-between">
            <span class="fw-bold text-muted">ระบบอาจารย์ที่ปรึกษาสโมสร</span>
            <span><i class="fas fa-user-circle"></i> อ. <?= session()->get('full_name') ?></span>
        </div>
        <div class="p-4">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>
</body>
</html>