<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>แก้ไขข้อมูลกรรมการ<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-edit"></i> แก้ไขข้อมูลกรรมการ
                </div>
                <div class="card-body">
                    
                    <form action="<?= base_url('admin/committee/update/' . $committee['committee_id']) ?>" method="post">
                        
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label fw-bold">รหัสนักศึกษา:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control-plaintext" value="<?= $committee['student_id'] ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label fw-bold">ชื่อ-นามสกุล:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control-plaintext" value="<?= $committee['full_name'] ?>" readonly>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-bold">ตำแหน่ง <span class="text-danger">*</span></label>
                            <select name="position_id" class="form-select" required>
                                <option value="">-- เลือกตำแหน่ง --</option>
                                <?php foreach($positions as $pos): ?>
                                    <option value="<?= $pos['position_id'] ?>" 
                                        <?= ($pos['position_id'] == $committee['position_id']) ? 'selected' : '' ?>>
                                        <?= $pos['position_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">ประจำปีการศึกษา <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-select" required>
                                <?php foreach($years as $year): ?>
                                    <option value="<?= $year['year_id'] ?>"
                                        <?= ($year['year_id'] == $committee['academic_year_id']) ? 'selected' : '' ?>>
                                        <?= $year['year_name'] ?> <?= $year['is_current'] ? '(ปัจจุบัน)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= base_url('admin/committee') ?>" class="btn btn-secondary">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกการแก้ไข</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>