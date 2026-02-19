<?= $this->extend('advisor/layout') ?>
<?= $this->section('title') ?>รายงานภาพรวมสโมสร<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h4 class="mb-4"><i class="fas fa-file-invoice text-info"></i> รายงานการดำเนินกิจกรรมสโมสร</h4>
    
    <div class="row">
        <?php foreach($activities as $act): ?>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <h5 class="card-title"><?= $act['activity_name'] ?></h5>
                    <p class="text-muted small mb-3">จัดเมื่อ: <?= date('d/m/Y', strtotime($act['start_date'])) ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                        <span>จำนวนนักศึกษาที่เข้าร่วม:</span>
                        <span class="h4 mb-0 text-info fw-bold"><?= $act['participant_count'] ?> คน</span>
                    </div>
                    
                    <a href="<?= base_url('advisor/report_participants/'.$act['activity_id']) ?>" class="btn btn-outline-info btn-sm w-100 mt-3">
                        <i class="fas fa-list"></i> ดูรายชื่อนักศึกษาที่เข้าร่วม
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>