<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>แก้ไขกิจกรรม<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> แก้ไขข้อมูลกิจกรรม</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('committee/activity/update/' . $activity['activity_id']) ?>" method="post">
                        
                        <div class="mb-3">
                            <label class="form-label">ชื่อกิจกรรม <span class="text-danger">*</span></label>
                            <input type="text" name="activity_name" class="form-control" required value="<?= $activity['activity_name'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">รายละเอียด</label>
                            <textarea name="description" class="form-control" rows="4"><?= $activity['description'] ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">วันเริ่มกิจกรรม</label>
                                <input type="datetime-local" name="start_date" class="form-control" required value="<?= date('Y-m-d\TH:i', strtotime($activity['start_date'])) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">วันสิ้นสุดกิจกรรม</label>
                                <input type="datetime-local" name="end_date" class="form-control" required value="<?= date('Y-m-d\TH:i', strtotime($activity['end_date'])) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">สถานที่จัดกิจกรรม</label>
                            <input type="text" name="location" class="form-control" required value="<?= $activity['location'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">สถานะกิจกรรม</label>
                            <select name="status" class="form-select">
                                <option value="planning" <?= $activity['status'] == 'planning' ? 'selected' : '' ?>>กำลังวางแผน (Planning)</option>
                                <option value="approved" <?= $activity['status'] == 'approved' ? 'selected' : '' ?>>เปิดรับสมัคร (Approved/Open)</option>
                                <option value="completed" <?= $activity['status'] == 'completed' ? 'selected' : '' ?>>ปิดรับสมัคร/เสร็จสิ้น</option>
                            </select>
                        </div>
                        
                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('committee/activities') ?>" class="btn btn-secondary">
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