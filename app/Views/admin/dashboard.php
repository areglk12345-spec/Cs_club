<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>ผู้ดูแลระบบ<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="alert alert-primary shadow-sm">
                <h4><i class="fas fa-user-shield"></i> ผู้ดูแลระบบ (Admin)</h4>
                <p class="mb-0">จัดการข้อมูลพื้นฐานของระบบ</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="fas fa-university fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">1. จัดการข้อมูลสาขา</h5>
                    <p class="card-text text-muted small">เพิ่ม/ลบ/แก้ไข รายชื่อสาขาวิชา</p>
                    <a href="<?= base_url('admin/majors') ?>" class="btn btn-outline-primary w-100">จัดการ</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="fas fa-users-cog fa-3x text-success mb-3"></i>
                    <h5 class="card-title">2. จัดการกรรมการสโมสร</h5>
                    <p class="card-text text-muted small">แต่งตั้งนักศึกษาเป็นกรรมการ</p>
                    <a href="<?= base_url('admin/committee') ?>" class="btn btn-outline-success w-100">จัดการ</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x text-info mb-3"></i>
                    <h5 class="card-title">3. จัดการปีการศึกษา</h5>
                    <p class="card-text text-muted small">กำหนดปีการศึกษาปัจจุบัน</p>
                    <a href="<?= base_url('admin/years') ?>" class="btn btn-outline-info w-100">จัดการ</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm hover-card">
        <div class="card-body text-center">
            <i class="fas fa-id-badge fa-3x text-warning mb-3"></i>
            <h5 class="card-title">4. จัดการตำแหน่ง</h5>
            <p class="card-text text-muted small">เพิ่มตำแหน่งต่างๆ ในสโมสร</p>
            
            <a href="<?= base_url('admin/positions') ?>" class="btn btn-outline-warning w-100">จัดการ</a>
            
        </div>
    </div>
</div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="fas fa-user-graduate fa-3x text-dark mb-3"></i>
                    <h5 class="card-title">5. จัดการข้อมูลนักศึกษา</h5>
                    <p class="card-text text-muted small">ดูรายชื่อ/รีเซ็ตรหัสนักศึกษา</p>
                    <a href="<?= base_url('admin/students') ?>" class="btn btn-outline-dark w-100">จัดการ</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-teacher fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">6. จัดการอาจารย์ที่ปรึกษา</h5>
                    <p class="card-text text-muted small">เพิ่มข้อมูลอาจารย์</p>
                    <a href="<?= base_url('admin/advisors') ?>" class="btn btn-outline-danger w-100">จัดการ</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                    <h5 class="card-title">7. รายงานระบบ</h5>
                    <p class="card-text text-muted small">รายงานสมาชิกสโมสร / ข้อมูลนักศึกษา</p>
                    <a href="<?= base_url('admin/reports') ?>" class="btn btn-outline-secondary w-100">เลือกดูรายงาน</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>