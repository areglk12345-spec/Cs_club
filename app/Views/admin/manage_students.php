<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>จัดการข้อมูลนักศึกษา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-user-graduate"></i> จัดการข้อมูลนักศึกษา</h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-list"></i> รายชื่อนักศึกษาทั้งหมด
        </div>
        <div class="card-body">
            
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%;">รหัสนักศึกษา</th>
                            <th style="width: 25%;">ชื่อ-นามสกุล</th>
                            <th>สาขาวิชา</th>
                            <th style="width: 15%;">เบอร์โทร</th>
                            <th style="width: 20%;" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($students)): ?>
                            <tr><td colspan="5" class="text-center text-muted p-4">ไม่พบข้อมูลนักศึกษา</td></tr>
                        <?php else: ?>
                            <?php foreach ($students as $std) : ?>
                            <tr>
                                <td class="fw-bold text-primary"><?= $std['student_id'] ?></td>
                                <td><?= $std['full_name'] ?></td>
                                <td><span class="badge bg-info text-dark"><?= $std['major_name'] ?></span></td>
                                <td><?= $std['phone_number'] ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/students/reset/'.$std['student_id']) ?>" 
                                       class="btn btn-sm btn-warning"
                                       title="รีเซ็ตรหัสผ่านเป็น 1234"
                                       onclick="return confirm('ต้องการรีเซ็ตรหัสผ่านของ <?= $std['full_name'] ?> เป็น 1234 ใช่หรือไม่?');">
                                       <i class="fas fa-key"></i> รีเซ็ต
                                    </a>
                                    <a href="<?= base_url('admin/students/edit/'.$std['student_id']) ?>" 
   class="btn btn-sm btn-warning me-1">
   <i class="fas fa-edit"></i> แก้ไข
</a>
                                    
                                    <a href="<?= base_url('admin/students/delete/'.$std['student_id']) ?>" 
                                       class="btn btn-sm btn-danger"
                                       title="ลบข้อมูล"
                                       onclick="return confirm('ยืนยันการลบนักศึกษา? ข้อมูลกิจกรรมและการเข้าชมรมจะหายไปทั้งหมด');">
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
<?= $this->endSection() ?>