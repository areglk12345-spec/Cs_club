<?= $this->extend('layouts/main') ?> <?= $this->section('title') ?>แก้ไขสาขาวิชา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> แก้ไขข้อมูลสาขาวิชา</h5>
                </div>
                <div class="card-body">
                    
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/major/update/' . $major['major_id']) ?>" method="post">
                        
                        <div class="mb-3">
                            <label class="form-label">ชื่อสาขาวิชา <span class="text-danger">*</span></label>
                            <input type="text" name="major_name" class="form-control" required 
                                   value="<?= esc($major['major_name']) ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/majors') ?>" class="btn btn-secondary">
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