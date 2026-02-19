<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>ภาพรวม<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h2>ยินดีต้อนรับ, <?= session()->get('full_name') ?></h2>
    <p class="text-muted">ระบบบริหารจัดการสำหรับคณะกรรมการสโมสรนักศึกษา</p>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">สมาชิกทั้งหมด</h5>
                    <p class="card-text display-4"><?= $count_members ?> <span class="fs-6">คน</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">กิจกรรมทั้งหมด</h5>
                    <p class="card-text display-4"><?= $count_activities ?> <span class="fs-6">งาน</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">รออนุมัติเข้าร่วม</h5>
                    <p class="card-text display-4"><?= $count_pending ?> <span class="fs-6">รายการ</span></p>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>