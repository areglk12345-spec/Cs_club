<?= $this->extend('advisor/layout') ?>
<?= $this->section('title') ?>รายชื่อผู้เข้าร่วมกิจกรรม<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-list-alt text-primary"></i> รายชื่อผู้เข้าร่วมกิจกรรม</h4>
        <a href="<?= base_url('advisor/reports') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-dark">กิจกรรม: <?= $activity['activity_name'] ?></h5>
            <small class="text-muted">สถานที่: <?= $activity['location'] ?> | วันที่: <?= date('d/m/Y', strtotime($activity['start_date'])) ?></small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">รหัสนักศึกษา</th>
                            <th width="30%">ชื่อ-นามสกุล</th>
                            <th width="25%">สาขาวิชา</th>
                            <th width="25%">วันที่ลงทะเบียน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($participants)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">ยังไม่มีรายชื่อนักศึกษาที่เข้าร่วมกิจกรรมนี้</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($participants as $index => $p): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td class="fw-bold"><?= $p['std_id'] ?></td>
                                <td><?= $p['full_name'] ?></td>
                                <td><?= $p['major_name'] ?? 'ไม่ระบุ' ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($p['register_date'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex gap-2">
    <button onclick="exportToExcel('reportTable')" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
    <button onclick="window.print()" class="btn btn-danger btn-sm">
        <i class="fas fa-file-pdf"></i> Export PDF (Print)
    </button>
</div>

<table class="table table-striped" id="reportTable">
    </table>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
function exportToExcel(tableID) {
    const table = document.getElementById(tableID);
    const wb = XLSX.utils.table_to_book(table, {sheet: "รายชื่อผู้เข้าร่วม"});
    XLSX.writeFile(wb, "Report_Participants.xlsx");
}
</script>

<style>
/* ตกแต่งตอนพิมพ์ PDF ให้ปุ่มหายไป */
@media print {
    .btn, .sidebar, .top-nav, .breadcrumb { display: none !important; }
    .content { padding: 0 !important; margin: 0 !important; width: 100% !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>
        </div>
        <div class="card-footer bg-light text-end py-3">
            <strong>รวมทั้งหมด: <?= count($participants) ?> คน</strong>
        </div>
    </div>
</div>
<?= $this->endSection() ?>