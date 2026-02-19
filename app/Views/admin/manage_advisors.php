<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>จัดการอาจารย์ที่ปรึกษา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-chalkboard-teacher"></i> จัดการอาจารย์ที่ปรึกษา</h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-user-plus"></i> เพิ่มอาจารย์ท่านใหม่
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/advisors/save') ?>" method="post">
                        
                        <h6 class="text-muted border-bottom pb-2 mb-3">ข้อมูลทั่วไป</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required placeholder="เช่น อ.สมชาย ใจดี">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" placeholder="example@college.ac.th">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="form-control" placeholder="08x-xxxxxxx">
                        </div>

                        <h6 class="text-muted border-bottom pb-2 mb-3 mt-4">ข้อมูลเข้าสู่ระบบ</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้ (Username) <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required placeholder="ภาษาอังกฤษเท่านั้น">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required placeholder="กำหนดรหัสผ่าน">
                        </div>

                        <button type="submit" class="btn btn-danger w-100 mt-2">
                            <i class="fas fa-save"></i> บันทึกข้อมูล
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-list"></i> รายชื่ออาจารย์ในระบบ
                </div>
                <div class="card-body">
                    
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif ?>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ข้อมูลเข้าระบบ</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>การติดต่อ</th>
                                    <th class="text-center" style="width: 15%;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($advisors)): ?>
                                    <tr><td colspan="4" class="text-center text-muted p-4">ยังไม่มีข้อมูลอาจารย์</td></tr>
                                <?php else: ?>
                                    <?php foreach ($advisors as $adv) : ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-danger"><?= $adv['username'] ?></span>
                                        </td>
                                        <td><?= $adv['full_name'] ?></td>
                                        <td>
                                            <small class="d-block text-muted"><i class="fas fa-envelope"></i> <?= $adv['email'] ?: '-' ?></small>
                                            <small class="d-block text-muted"><i class="fas fa-phone"></i> <?= $adv['phone'] ?: '-' ?></small>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('admin/advisors/edit/'.$adv['advisor_id']) ?>" 
   class="btn btn-sm btn-warning me-1">
   <i class="fas fa-edit"></i> แก้ไข
</a>
                                            <a href="<?= base_url('admin/advisors/delete/'.$adv['advisor_id']) ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('ยืนยันการลบอาจารย์ <?= $adv['full_name'] ?>?');">
                                               <i class="fas fa-trash"></i> ลบ
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>