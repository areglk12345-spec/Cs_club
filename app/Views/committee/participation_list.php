<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>ตรวจสอบการเข้าร่วม<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h3><i class="fas fa-clipboard-check"></i> เลือกกิจกรรมเพื่อเช็คชื่อ</h3>
<hr>
<div class="row">
    <?php foreach($activities as $act): ?>
    <div class="col-md-6 mb-3">
        <div class="card h-100 border-primary">
            <div class="card-body">
                <h5 class="card-title text-primary"><?= $act['activity_name'] ?></h5>
                <p class="card-text text-muted small">
                    <i class="fas fa-clock"></i> <?= date('d/m/Y', strtotime($act['start_date'])) ?><br>
                    <i class="fas fa-map-marker-alt"></i> <?= $act['location'] ?>
                </p>
                <a href="<?= base_url('committee/participation/'.$act['activity_id']) ?>" class="btn btn-outline-primary w-100">
                    <i class="fas fa-users"></i> ดูรายชื่อผู้สมัคร / เช็คชื่อ
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>