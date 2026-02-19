<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>ระบบกรรมการสโมสร<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-user-shield text-danger"></i> ระบบจัดการกิจกรรม (กรรมการ)</h4>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">กลับหน้าหลัก</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ชื่อกิจกรรม</th>
                        <th>วันที่จัด</th>
                        <th>สถานะกิจกรรม</th>
                        <th width="20%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($activities as $act): ?>
                    <tr>
                        <td>
                            <strong><?= $act['activity_name'] ?></strong><br>
                            <small class="text-muted"><?= $act['location'] ?></small>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($act['start_date'])) ?></td>
                        <td>
                            <?php if($act['status'] == 'approved'): ?>
                                <span class="badge bg-success">เปิดรับสมัคร</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">วางแผน/ปิดรับ</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('committee/activity/'.$act['activity_id']) ?>" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-users"></i> เช็คชื่อ/อนุมัติ
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>