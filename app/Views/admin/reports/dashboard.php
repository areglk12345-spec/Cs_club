<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>ระบบรายงาน<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-print"></i> ระบบรายงานสารสนเทศ</h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">กลับหน้าหลัก</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">รายงานสมาชิกสโมสร</h5>
                        <p class="text-muted small mb-2">แสดงรายชื่อคณะกรรมการสโมสรแยกตามปีการศึกษา</p>
                        <a href="<?= base_url('admin/reports/committee') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> ดูรายงาน
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded me-3">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">รายงานข้อมูลนักศึกษา</h5>
                        <p class="text-muted small mb-2">แสดงรายชื่อนักศึกษาทั้งหมดในระบบ</p>
                        <a href="<?= base_url('admin/reports/students') ?>" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye"></i> ดูรายงาน
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>