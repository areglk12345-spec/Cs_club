<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>จัดการตำแหน่งสโมสร<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-id-badge"></i> จัดการตำแหน่งสโมสร</h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-plus-circle"></i> เพิ่มตำแหน่งใหม่
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/positions/save') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">ชื่อตำแหน่ง</label>
                            <input type="text" name="position_name" class="form-control" placeholder="เช่น นายกสโมสร, เหรัญญิก" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">บันทึกข้อมูล</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-list"></i> รายชื่อตำแหน่งทั้งหมด
                </div>
                <div class="card-body">
                    
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif ?>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif ?>

                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10%;" class="text-center">ลำดับ</th>
                                <th>ชื่อตำแหน่ง</th>
                                <th style="width: 20%;" class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($positions)): ?>
                                <tr><td colspan="3" class="text-center text-muted p-3">ยังไม่มีข้อมูลตำแหน่ง</td></tr>
                            <?php else: ?>
                                
                                <?php $i = 1; ?>
                                
                                <?php foreach ($positions as $p) : ?>
                                <tr>
                                    <td class="text-center"><?= $i++ ?></td>
                                    
                                    <td><?= esc($p['position_name']) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/positions/edit/'.$p['position_id']) ?>" 
                                           class="btn btn-sm btn-warning me-1">
                                           <i class="fas fa-edit"></i> แก้ไข
                                        </a>
                                        <a href="<?= base_url('admin/positions/delete/'.$p['position_id']) ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('ยืนยันการลบตำแหน่ง <?= $p['position_name'] ?>?');">
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
<?= $this->endSection() ?>