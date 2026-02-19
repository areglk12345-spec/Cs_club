<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>รายชื่อคณะกรรมการ<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-primary">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0"><i class="fas fa-user-tie"></i> รายชื่อคณะกรรมการสโมสรนักศึกษา</h5>
        <span class="badge bg-light text-primary"><?= count($students) ?> ท่าน</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="10%">#</th>
                        <th width="15%">รหัสนักศึกษา</th>
                        <th width="30%">ชื่อ-นามสกุล</th>
                        <th width="25%">ตำแหน่ง</th>
                        <th width="20%">เบอร์โทรศัพท์</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($students)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">ไม่พบข้อมูลคณะกรรมการ</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($students as $index => $std): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $std['student_id'] ?></td>
                            <td><?= $std['full_name'] ?></td>
                            <td>
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                    <i class="fas fa-star"></i> <?= $std['position_name'] ?>
                                </span>
                            </td>
                            <td>
                                <?= $std['phone_number'] ?? '-' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>