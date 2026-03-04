<!DOCTYPE html>
<html lang="th" data-bs-theme="light">
<?= view('layouts/_header') ?>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>"><i class="fas fa-university"></i> Sci-Tech Club</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (session()->get('is_logged_in')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <?php
                                $avatar = session()->get('avatar');
                                $avatarPath = !empty($avatar) ? base_url('uploads/avatars/' . $avatar) : base_url('img/default-avatar.png');
                                ?>
                                <img src="<?= $avatarPath ?>" alt="Avatar" class="rounded-circle me-2"
                                    style="width: 30px; height: 30px; object-fit: cover; border: 1px solid rgba(255,255,255,0.5);">
                                <span>คุณ<?= session()->get('user_name') ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="<?= base_url('dashboard') ?>"><i
                                            class="fas fa-tachometer-alt me-2"></i> หน้าหลัก</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i
                                            class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Theme Toggle -->
                    <li class="nav-item dropdown ms-lg-3">
                        <button class="btn btn-link nav-link dropdown-toggle d-flex align-items-center" id="bd-theme"
                            type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
                            <i class="fas fa-circle-half-stroke me-2"></i>
                            <span class="d-lg-none">เปลี่ยนธีม</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                            <li><button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="light"><i class="fas fa-sun me-2 opacity-50"></i>
                                    Light</button></li>
                            <li><button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="dark"><i class="fas fa-moon me-2 opacity-50"></i> Dark</button>
                            </li>
                            <li><button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="auto"><i class="fas fa-circle-half-stroke me-2 opacity-50"></i>
                                    Auto</button></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <?= $this->renderSection('content') ?>
    </div>

    <?= view('layouts/_footer') ?>
</body>

</html>