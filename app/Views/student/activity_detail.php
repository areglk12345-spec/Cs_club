<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>รายละเอียดกิจกรรม<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">หน้าแรก</a></li>
            <li class="breadcrumb-item active">รายละเอียดกิจกรรม</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <!-- รูปปกกิจกรรม -->
                <?php if (!empty($activity['cover_image'])): ?>
                    <img src="<?= base_url('uploads/activities/' . $activity['cover_image']) ?>" class="card-img-top"
                        style="max-height: 300px; object-fit: cover;">
                <?php endif; ?>

                <div class="card-body p-4">
                    <h2 class="text-primary mb-3"><?= $activity['activity_name'] ?></h2>
                    <span class="badge bg-success mb-3">เปิดรับสมัคร</span>

                    <h5 class="mt-3 text-muted">รายละเอียด</h5>
                    <p class="lead" style="font-size: 1.1rem;"><?= nl2br($activity['description']) ?></p>

                    <hr>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold"><i class="fas fa-calendar-alt text-danger"></i> เริ่มกิจกรรม:
                        </div>
                        <div class="col-md-8"><?= date('d/m/Y H:i', strtotime($activity['start_date'])) ?></div>
                    </div>

                    <?php if (!empty($activity['end_date'])): ?>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold"><i class="fas fa-flag-checkered text-danger"></i> สิ้นสุด:</div>
                            <div class="col-md-8"><?= date('d/m/Y H:i', strtotime($activity['end_date'])) ?></div>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold"><i class="fas fa-map-marker-alt text-primary"></i> สถานที่:</div>
                        <div class="col-md-8"><?= $activity['location'] ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">การลงทะเบียน</div>
                <div class="card-body text-center">

                    <?php if (isset($is_registered) && $is_registered): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle fa-3x mb-2"></i><br>
                            คุณลงทะเบียนเรียบร้อยแล้ว
                            <?php if (isset($reg_status) && $reg_status == 'pending'): ?>
                                <br><small>(รอการอนุมัติ)</small>
                            <?php endif; ?>
                        </div>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary w-100">กลับหน้าหลัก</a>

                    <?php else: ?>
                        <i class="fas fa-user-plus fa-4x text-primary mb-3"></i>
                        <p>ยืนยันสิทธิ์เข้าร่วมกิจกรรมนี้</p>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger small"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>

                        <form action="<?= base_url('student/activity/register') ?>" method="post">
                            <input type="hidden" name="activity_id" value="<?= $activity['activity_id'] ?>">
                            <button type="submit" class="btn btn-primary w-100 btn-lg"
                                onclick="return confirm('ยืนยันการลงทะเบียน?');">
                                ลงทะเบียนเข้าร่วม
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>