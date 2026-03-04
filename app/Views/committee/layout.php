<!DOCTYPE html>
<html lang="th" data-bs-theme="light">
<?= view('layouts/_header') ?>

<body>

    <div class="wrapper">
        <nav class="sidebar shadow">
            <div class="sidebar-header text-center">
                <h4 class="mb-0"><i class="fas fa-user-shield"></i> Committee</h4>
                <small class="opacity-75">จัดการสโมสรนักศึกษา</small>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="<?= base_url('committee/dashboard') ?>"
                        class="<?= uri_string() == 'committee/dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> แดชบอร์ด
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('committee/members') ?>"
                        class="<?= strpos(uri_string(), 'members') !== false ? 'active' : '' ?>">
                        <i class="fas fa-users me-2"></i> 1. สมาชิกสโมสร
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('committee/activities') ?>"
                        class="<?= strpos(uri_string(), 'activit') !== false ? 'active' : '' ?>">
                        <i class="fas fa-calendar-alt me-2"></i> 2. จัดการกิจกรรม
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('committee/check_participation') ?>"
                        class="<?= strpos(uri_string(), 'participation') !== false ? 'active' : '' ?>">
                        <i class="fas fa-clipboard-check me-2"></i> 3. ตรวจสอบเข้าร่วม
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('committee/reports') ?>"
                        class="<?= strpos(uri_string(), 'reports') !== false ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar me-2"></i> 4. รายงานผล
                    </a>
                </li>
            </ul>

            <div class="text-center mt-auto p-4">
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm w-100"
                    onclick="return confirm('ยืนยันออกจากระบบ?');">
                    <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
                </a>
            </div>
        </nav>

        <div class="content">
            <nav class="navbar navbar-expand-lg top-navbar bg-body shadow-sm border-bottom">
                <div class="container-fluid">
                    <span class="navbar-text fw-bold text-primary">
                        <i class="fas fa-university"></i> Sci-Tech Club Management
                    </span>

                    <div class="ms-auto d-flex align-items-center">
                        <!-- Theme Toggle -->
                        <div class="dropdown me-3">
                            <button class="btn btn-link nav-link dropdown-toggle d-flex align-items-center"
                                id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown">
                                <i class="fas fa-circle-half-stroke"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><button type="button" class="dropdown-item d-flex align-items-center"
                                        data-bs-theme-value="light"><i class="fas fa-sun me-2 opacity-50"></i>
                                        Light</button></li>
                                <li><button type="button" class="dropdown-item d-flex align-items-center"
                                        data-bs-theme-value="dark"><i class="fas fa-moon me-2 opacity-50"></i>
                                        Dark</button></li>
                                <li><button type="button" class="dropdown-item d-flex align-items-center"
                                        data-bs-theme-value="auto"><i
                                            class="fas fa-circle-half-stroke me-2 opacity-50"></i> Auto</button></li>
                            </ul>
                        </div>

                        <div class="me-3 d-flex align-items-center">
                            <div class="text-end d-none d-sm-block me-2">
                                <span class="d-block fw-bold small"><?= session()->get('full_name') ?></span>
                                <small class="text-muted small">Committee</small>
                            </div>
                            <?php
                            $avatar = session()->get('avatar');
                            $avatarPath = !empty($avatar) ? base_url('uploads/avatars/' . $avatar) : base_url('img/default-avatar.png');
                            ?>
                            <img src="<?= $avatarPath ?>" alt="Avatar" class="rounded-circle shadow-sm"
                                style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #0d6efd;">
                        </div>
                    </div>
                </div>
            </nav>

            <div class="main-content flex-grow-1 py-4">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>

            <?= view('layouts/_footer') ?>
        </div>
    </div>
</body>

</html>