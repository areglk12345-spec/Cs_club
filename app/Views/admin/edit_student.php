<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>แก้ไขข้อมูลนักศึกษา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-info"> <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> แก้ไขข้อมูลนักศึกษา</h5>
                </div>
                <div class="card-body">

                    <form action="<?= base_url('admin/student/update/' . $student['student_id']) ?>" method="post">
                        
                        <div class="mb-3">
                            <label class="form-label">รหัสนักศึกษา (ไม่สามารถแก้ไขได้)</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($student['student_id']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required 
                                   value="<?= esc($student['full_name']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone_number" class="form-control" 
                                   value="<?= esc($student['phone_number']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">สาขาวิชา</label>
                            <select name="major_id" class="form-select" required>
                                <option value="">-- เลือกสาขาวิชา --</option>
                                <?php foreach($majors as $m): ?>
                                    <option value="<?= $m['major_id'] ?>" 
                                        <?= ($student['major_id'] == $m['major_id']) ? 'selected' : '' ?>>
                                        <?= $m['major_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/students') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-info text-white px-4">
                                <i class="fas fa-save"></i> บันทึกข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>