<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>สร้างกิจกรรมใหม่<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> สร้างกิจกรรมใหม่</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('committee/activity/save') ?>" method="post"
                        enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">รูปหน้าปกกิจกรรม</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                            <small class="text-muted">แนะนำขนาด 800x400 px หรืออัตราส่วน 2:1</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ชื่อกิจกรรม <span class="text-danger">*</span></label>
                            <input type="text" name="activity_name" class="form-control" required
                                placeholder="เช่น กิจกรรมรับน้อง, อบรม Python">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">รายละเอียด</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="รายละเอียดกิจกรรม..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">วันเริ่มกิจกรรม <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">วันสิ้นสุดกิจกรรม <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">สถานที่จัดกิจกรรม <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" required
                                placeholder="เช่น ห้องประชุม 1, ลานกิจกรรม">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">อาจารย์ที่ปรึกษาโครงการ <span class="text-danger">*</span></label>
                            <select name="advisors_id" class="form-select" required>
                                <option value="">--- กรุณาเลือกอาจารย์ที่ปรึกษา ---</option>
                                <?php foreach ($advisors as $adv): ?>
                                    <option value="<?= $adv['advisor_id'] ?>">อ. <?= $adv['full_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">กิจกรรมจะถูกส่งไปให้อาจารย์ท่านนี้ตรวจสอบและอนุมัติ</small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('committee/activities') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> ย้อนกลับ
                            </a>

                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save"></i> บันทึกกิจกรรม
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>