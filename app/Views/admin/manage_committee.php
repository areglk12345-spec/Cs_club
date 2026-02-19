<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>จัดการคณะกรรมการสโมสร<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-users-cog"></i> จัดการคณะกรรมการสโมสร</h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-user-plus"></i> แต่งตั้งกรรมการใหม่
        </div>
        <div class="card-body">
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif ?>
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif ?>

            <form action="<?= base_url('admin/committee/save') ?>" method="post" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">รหัสนักศึกษา</label>
                    <input type="text" name="student_id" class="form-control" placeholder="กรอกรหัสนักศึกษาที่มีในระบบ" required>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">ตำแหน่ง</label>
                    <select name="position_id" class="form-select" required>
                        <option value="">-- เลือกตำแหน่ง --</option>
                        <?php foreach($positions as $pos): ?>
                            <option value="<?= $pos['position_id'] ?>"><?= $pos['position_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">ปีการศึกษา</label>
                    <select name="academic_year_id" class="form-select" required>
                        <?php foreach($years as $year): ?>
                            <option value="<?= $year['year_id'] ?>">
                                <?= $year['year_name'] ?> <?= $year['is_current'] ? '(ปัจจุบัน)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-list"></i> รายชื่อคณะกรรมการทั้งหมด
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ปีการศึกษา</th>
                        <th>รหัสนักศึกษา</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>ตำแหน่ง</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($committee_list)): ?>
                        <tr><td colspan="5" class="text-center text-muted">ยังไม่มีข้อมูลกรรมการ</td></tr>
                    <?php else: ?>
                        <?php foreach ($committee_list as $row) : ?>
                        <tr>
                            <td><?= $row['year_name'] ?></td>
                            <td><?= $row['student_id'] ?></td>
                            <td><?= $row['full_name'] ?></td>
                            <td><span class="badge bg-info text-dark"><?= $row['position_name'] ?></span></td>
                            <td class="text-center">
    <a href="<?= base_url('admin/committee/edit/'.$row['committee_id']) ?>" 
       class="btn btn-sm btn-warning me-1">
       <i class="fas fa-edit"></i> แก้ไข
    </a>

    <a href="<?= base_url('admin/committee/delete/'.$row['committee_id']) ?>" 
       class="btn btn-sm btn-outline-danger"
       onclick="return confirm('ยืนยันที่จะถอนชื่อกรรมการ <?= $row['full_name'] ?> ออกจากตำแหน่ง?');">
       <i class="fas fa-user-times"></i> ถอนชื่อ
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
<?= $this->endSection() ?>