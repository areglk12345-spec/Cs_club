<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>รายชื่อผู้สมัคร<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('committee/check_participation') ?>">เลือกกิจกรรม</a></li>
                <li class="breadcrumb-item active">รายชื่อผู้สมัคร</li>
            </ol>
        </nav>
        
        <a href="<?= base_url('committee/check_participation') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>

    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> กิจกรรม: <?= $activity['activity_name'] ?>
            </h5>
            <span class="badge bg-light text-primary"><?= count($participants) ?> คน</span>
        </div>
        <div class="card-body">
            
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">รหัสนักศึกษา</th>
                            <th width="30%">ชื่อ-นามสกุล</th>
                            <th width="15%" class="text-center">สถานะ</th>
                            <th width="20%" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($participants)): ?>
                            <tr><td colspan="5" class="text-center text-muted p-4">ยังไม่มีผู้สมัครในกิจกรรมนี้</td></tr>
                        <?php else: ?>
                            <?php foreach($participants as $index => $p): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $p['std_id'] ?></td>
                                <td><?= $p['full_name'] ?></td>
                                <td class="text-center">
                                    <?php if($p['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">รออนุมัติ</span>
                                    <?php elseif($p['status'] == 'approved'): ?>
                                        <span class="badge bg-success">ผ่าน</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">ไม่ผ่าน</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($p['status'] == 'pending'): ?>
                                        <a href="<?= base_url('committee/status/'.$p['registration_id'].'/approved') ?>" class="btn btn-success btn-sm" title="อนุมัติ">
                                            <i class="fas fa-check"></i> อนุมัติ
                                        </a>
                                        <a href="<?= base_url('committee/status/'.$p['registration_id'].'/rejected') ?>" class="btn btn-danger btn-sm" title="ปฏิเสธ" onclick="return confirm('ยืนยันปฏิเสธ?');">
                                            <i class="fas fa-times"></i> ปฏิเสธ
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-light btn-sm text-muted border" disabled>ดำเนินการแล้ว</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>