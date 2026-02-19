<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?> - กรรมการสโมสร</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        
        /* Sidebar Styling */
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background-color: #2c3e50;
            color: #ecf0f1;
            transition: all 0.3s;
        }
        .sidebar-header { padding: 20px; background: #243342; }
        .sidebar ul.components { padding: 20px 0; }
        .sidebar ul li a {
            padding: 10px 20px;
            font-size: 1.1em;
            display: block;
            color: #bdc3c7;
            text-decoration: none;
        }
        .sidebar ul li a:hover, .sidebar ul li a.active {
            color: #fff;
            background: #34495e;
            border-left: 4px solid #3498db;
        }
        
        /* Content Styling */
        .content { width: 100%; }
        .top-navbar { background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 15px 30px; }
        .main-content { padding: 30px; }
    </style>
</head>
<body>

<div class="wrapper">
    <nav class="sidebar">
        <div class="sidebar-header text-center">
            <h4><i class="fas fa-user-shield"></i> Committee</h4>
            <small>ระบบจัดการสโมสร</small>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="<?= base_url('committee/dashboard') ?>" class="<?= uri_string() == 'committee/dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt me-2"></i> แดชบอร์ด
                </a>
            </li>
            <li>
                <a href="<?= base_url('committee/members') ?>" class="<?= strpos(uri_string(), 'members') !== false ? 'active' : '' ?>">
                    <i class="fas fa-users me-2"></i> 1. สมาชิกสโมสร
                </a>
            </li>
            <li>
                <a href="<?= base_url('committee/activities') ?>" class="<?= strpos(uri_string(), 'activit') !== false ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt me-2"></i> 2. จัดการกิจกรรม
                </a>
            </li>
            <li>
                <a href="<?= base_url('committee/check_participation') ?>" class="<?= strpos(uri_string(), 'participation') !== false ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-check me-2"></i> 3. ตรวจสอบเข้าร่วม
                </a>
            </li>
            <li>
                <a href="<?= base_url('committee/reports') ?>" class="<?= strpos(uri_string(), 'reports') !== false ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar me-2"></i> 4. รายงานผล
                </a>
            </li>
        </ul>
        
        <div class="text-center mt-5 d-none d-md-block">
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm w-75" onclick="return confirm('ยืนยันออกจากระบบ?');">
                <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
            </a>
        </div>
    </nav>

    <div class="content">
        <nav class="navbar navbar-expand-lg top-navbar">
            <div class="container-fluid">
                <span class="navbar-text fw-bold text-primary">
                    <i class="fas fa-university"></i> ระบบบริหารจัดการกิจกรรมนักศึกษา
                </span>
                
                <div class="ms-auto d-flex align-items-center">
                    <div class="me-3 text-end d-none d-sm-block">
                        <span class="d-block fw-bold text-dark"><?= session()->get('full_name') ?></span>
                        <small class="text-muted">สถานะ: กรรมการสโมสร</small>
                    </div>
                    
                    <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันออกจากระบบ?');">
                        <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
                    </a>
                </div>
            </div>
        </nav>

        <div class="main-content">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>