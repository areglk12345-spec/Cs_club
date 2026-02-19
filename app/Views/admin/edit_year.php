<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>แก้ไขปีการศึกษา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> แก้ไขปีการศึกษา</h5>
                </div>
                <div class="card-body">

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/year/update/' . $year['year_id']) ?>" method="post">
                        
                        <div class="mb-3">
                            <label class="form-label">ปีการศึกษา (พ.ศ.) <span class="text-danger">*</span></label>
                            <input type="number" name="year_name" class="form-control" required 
                                   value="<?= esc($year['year_name']) ?>">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_current" class="form-check-input" id="checkCurrent" value="1" 
                                   <?= ($year['is_current'] == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="checkCurrent">ตั้งเป็นปีการศึกษาปัจจุบันทันที</label>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/years') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-warning px-4">
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