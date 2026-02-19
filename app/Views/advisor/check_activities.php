<?= $this->extend('advisor/layout') ?>
<?= $this->section('title') ?>ตรวจสอบกิจกรรม<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary"><i class="fas fa-tasks"></i> รายการกิจกรรมที่รอการตรวจสอบ</h5>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อกิจกรรม</th>
                            <th>วันที่จัด</th>
                            <th>สถานที่</th>
                            <th class="text-center">สถานะ</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($activities as $act): ?>
                        <tr>
                            <td>
                                <strong><?= $act['activity_name'] ?></strong><br>
                                <small class="text-muted"><?= mb_substr($act['description'], 0, 50) ?>...</small>
                            </td>
                            <td><?= date('d/m/Y', strtotime($act['start_date'])) ?></td>
                            <td><?= $act['location'] ?></td>
                            <td class="text-center">
    <?php if($act['status'] == 'planning'): ?>
        
        <a href="<?= base_url('advisor/approve/'.$act['activity_id']) ?>" 
           class="btn btn-success btn-sm mb-1" 
           onclick="return confirm('ยืนยันการอนุมัติ?');">
            <i class="fas fa-check"></i> อนุมัติ
        </a>

        <a href="<?= base_url('advisor/reject/'.$act['activity_id']) ?>" 
           class="btn btn-danger btn-sm mb-1" 
           onclick="return confirm('ยืนยันว่า ไม่ผ่าน การอนุมัติ? กรรมการจะต้องส่งเรื่องใหม่');">
            <i class="fas fa-times"></i> ไม่อนุมัติ
        </a>

    <?php elseif($act['status'] == 'rejected'): ?>
        <span class="badge bg-danger">ไม่ผ่านอนุมัติ</span>
    <?php else: ?>
        <button class="btn btn-light btn-sm border" disabled>ดำเนินการแล้ว</button>
    <?php endif; ?>
</td>
                            
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>