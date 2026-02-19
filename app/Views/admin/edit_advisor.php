<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>แก้ไขข้อมูลอาจารย์<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> แก้ไขข้อมูลอาจารย์</h5>
                </div>
                <div class="card-body">

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/advisor/update/' . $advisor['advisor_id']) ?>" method="post">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username (สำหรับเข้าระบบ) <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required 
                                       value="<?= esc($advisor['username']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">รหัสผ่านใหม่</label>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="ปล่อยว่างหากไม่ต้องการเปลี่ยน">
                                <small class="text-muted">กรอกเฉพาะเมื่อต้องการเปลี่ยนรหัสผ่าน</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required 
                                   value="<?= esc($advisor['full_name']) ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">อีเมล</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?= esc($advisor['email']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="<?= esc($advisor['phone']) ?>">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/advisors') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save"></i> บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>