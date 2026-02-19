<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>จัดการปีการศึกษา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-calendar-alt"></i> จัดการปีการศึกษา</h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-plus-circle"></i> เพิ่มปีการศึกษาใหม่
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/years/save') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">ปีการศึกษา (พ.ศ.)</label>
                            <input type="number" name="year_name" class="form-control" placeholder="เช่น 2567" required>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_current" value="1" id="checkCurrent">
                            <label class="form-check-label" for="checkCurrent">
                                ตั้งเป็นปีการศึกษาปัจจุบันทันที
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">บันทึกข้อมูล</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-list"></i> รายการปีการศึกษาทั้งหมด
                </div>
                <div class="card-body">
                    
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif ?>

                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ปีการศึกษา</th>
                                <th>สถานะ</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($years)): ?>
                                <tr><td colspan="3" class="text-center text-muted">ไม่พบข้อมูล</td></tr>
                            <?php else: ?>
                                <?php foreach ($years as $y) : ?>
                                <tr class="<?= ($y['is_current']) ? 'table-success' : '' ?>">
                                    <td class="align-middle fw-bold"><?= $y['year_name'] ?></td>
                                    <td class="align-middle">
                                        <?php if($y['is_current']): ?>
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> ปัจจุบัน</span>
                                        <?php else: ?>
                                            <a href="<?= base_url('admin/years/set_current/'.$y['year_id']) ?>" 
                                               class="btn btn-sm btn-outline-secondary"
                                               onclick="return confirm('ต้องการเปลี่ยนปีปัจจุบันเป็น <?= $y['year_name'] ?> ใช่หรือไม่?')">
                                               ตั้งเป็นปีปัจจุบัน
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="<?= base_url('admin/years/edit/'.$y['year_id']) ?>" 
   class="btn btn-sm btn-warning me-1">
   <i class="fas fa-edit"></i> แก้ไข
</a>
          
                                        <a href="<?= base_url('admin/years/delete/'.$y['year_id']) ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('ยืนยันการลบปี <?= $y['year_name'] ?>?');">
                                           <i class="fas fa-trash"></i> ลบ
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            <?php endif; ?>
                            <?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>