<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>จัดการข้อมูลสาขา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-university"></i> จัดการข้อมูลสาขาวิชา</h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">เพิ่มสาขาใหม่</div>
                <div class="card-body">
                    <form action="<?= base_url('admin/majors/save') ?>" method="post">
                        <div class="mb-3">
                            <label>ชื่อสาขาวิชา</label>
                            <input type="text" name="major_name" class="form-control" required placeholder="เช่น วิทยาการคอมพิวเตอร์">
                        </div>
                        <button type="submit" class="btn btn-success w-100">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th>ชื่อสาขาวิชา</th>
                                <th style="width: 15%;">จัดการ</th>
                            </tr>
                        <thead>
    <tr>
        <th style="width: 10%;">ลำดับ</th> <th>ชื่อสาขาวิชา</th>
        <th style="width: 20%;">จัดการ</th>
    </tr>
</thead>
<tbody>
    <?php $i = 1; // 1. ประกาศตัวแปรเริ่มนับที่ 1 ไว้ก่อนเริ่มวนลูป ?> 
    
    <?php foreach ($majors as $m) : ?>
    <tr>
        <td class="text-center"><?= $i++ ?></td> 
        
        <td><?= esc($m['major_name']) ?></td>
        <td>
            <a href="<?= base_url('admin/major/edit/'.$m['major_id']) ?>" class="btn btn-sm btn-warning me-1">
                <i class="fas fa-edit"></i> แก้ไข
            </a>
            <a href="<?= base_url('admin/major/delete/'.$m['major_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบ?');">
                <i class="fas fa-trash"></i> ลบ
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>