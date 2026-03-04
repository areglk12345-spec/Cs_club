<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>ผลการสแกน
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center">
                    <?php if ($status == 'success'): ?>
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-5x text-success animate__animated animate__bounceIn"></i>
                        </div>
                        <h2 class="fw-bold text-success mb-3">
                            <?= $message ?>
                        </h2>
                        <div class="p-3 bg-light rounded-3 mb-4">
                            <h5 class="mb-1 fw-bold">
                                <?= $activity['activity_name'] ?>
                            </h5>
                            <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt"></i>
                                <?= $activity['location'] ?>
                            </p>
                        </div>
                    <?php elseif ($status == 'warning'): ?>
                        <div class="mb-4">
                            <i
                                class="fas fa-exclamation-circle fa-5x text-warning animate__animated animate__headShake"></i>
                        </div>
                        <h2 class="fw-bold text-warning mb-2">เช็คอินไปแล้ว</h2>
                        <p class="text-muted mb-4">
                            <?= $message ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-4">
                            <i class="fas fa-times-circle fa-5x text-danger animate__animated animate__shakeX"></i>
                        </div>
                        <h2 class="fw-bold text-danger mb-2">เกิดข้อผิดพลาด</h2>
                        <p class="text-muted mb-4">
                            <?= $message ?>
                        </p>
                    <?php endif; ?>

                    <div class="d-grid gap-2">
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-primary btn-lg rounded-pill">
                            <i class="fas fa-home"></i> กลับหน้าหลัก
                        </a>
                        <a href="<?= base_url('student/history') ?>"
                            class="btn btn-outline-secondary btn-lg rounded-pill">
                            <i class="fas fa-history"></i> ดูประวัติกิจกรรม
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }
</style>
<?= $this->endSection() ?>