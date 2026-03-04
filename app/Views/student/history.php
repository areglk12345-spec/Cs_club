<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>ประวัติการเข้าร่วมกิจกรรม<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ประวัติกิจกรรม</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-history"></i> ประวัติการเข้าร่วมกิจกรรมของฉัน</h5>
        </div>
        <div class="card-body">

            <?php if (empty($history)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">คุณยังไม่เคยลงทะเบียนเข้าร่วมกิจกรรมใดๆ</p>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-search"></i> ค้นหากิจกรรมที่เปิดรับ
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">ชื่อกิจกรรม</th>
                                <th width="20%">วันที่จัดกิจกรรม</th>
                                <th width="20%">วันที่ลงทะเบียน</th>
                                <th width="15%" class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= $row['activity_name'] ?></strong><br>
                                        <small class="text-muted"><i class="fas fa-map-marker-alt"></i>
                                            <?= $row['location'] ?></small>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['start_date'])) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['register_date'])) ?></td>
                                    <td class="text-center">
                                        <?php if ($row['status'] == 'approved'): ?>
                                            <span class="badge bg-success rounded-pill px-3 mb-1">
                                                <i class="fas fa-check-circle"></i> อนุมัติแล้ว
                                            </span>
                                            <?php if (!empty($row['checkin_time'])): ?>
                                                <div class="mt-1">
                                                    <?php if (isset($row['rating'])): ?>
                                                        <div class="text-warning mb-1">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="<?= ($i <= $row['rating']) ? 'fas' : 'far' ?> fa-star small"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <a href="<?= base_url('student/feedback/' . $row['activity_id']) ?>"
                                                            class="btn btn-sm btn-outline-secondary" style="font-size: 0.7rem;">
                                                            แก้ไขประเมิน
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= base_url('student/feedback/' . $row['activity_id']) ?>"
                                                            class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-star"></i> ประเมินผล
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif ($row['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger rounded-pill px-3">
                                                <i class="fas fa-times-circle"></i> ไม่ผ่าน
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary rounded-pill px-3">
                                                <i class="fas fa-clock"></i> รออนุมัติ
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?= $this->endSection() ?>