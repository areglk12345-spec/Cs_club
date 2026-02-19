<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>รายงานผล<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h3 class="mb-4"><i class="fas fa-chart-pie"></i> รายงานสรุปผลกิจกรรม</h3>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">จำนวนผู้เข้าร่วมกิจกรรมแต่ละโครงการ</h5>
    </div>
    <div class="card-body">
        <?php if(empty($report_participation)): ?>
            <p class="text-center text-muted py-3">ยังไม่มีข้อมูลกิจกรรมที่เสร็จสิ้น</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ชื่อกิจกรรม</th>
                            <th class="text-center">จำนวนผู้เข้าร่วม (คน)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($report_participation as $row): ?>
                        <tr>
                            <td><?= $row['activity_name'] ?></td>
                            <td class="text-center fw-bold text-primary"><?= $row['total_joined'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> 
    สามารถนำข้อมูลนี้ไปใช้ประกอบการทำรูปเล่มรายงานสรุปประจำปีเสนออาจารย์ที่ปรึกษาได้
</div>
<?= $this->endSection() ?>