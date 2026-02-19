<?= $this->extend('advisor/layout') ?>
<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body text-center">
                <h6>กิจกรรมทั้งหมด</h6>
                <h2 class="display-6 fw-bold"><?= $total_activities ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark shadow-sm">
            <div class="card-body text-center">
                <h6>รออนุมัติ (Planning)</h6>
                <h2 class="display-6 fw-bold"><?= $pending_count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body text-center">
                <h6>นักศึกษาในระบบ</h6>
                <h2 class="display-6 fw-bold"><?= $total_students ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-chart-bar"></i> กราฟแสดงจำนวนผู้เข้าร่วมกิจกรรม</h5>
            </div>
            <div class="card-body">
                <canvas id="advisorChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark"><i class="fas fa-list"></i> รายละเอียดกิจกรรมทั้งหมด</h5>
                <div class="btn-group">
                    <button onclick="exportToExcel('advisorTable')" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Excel</button>
                    <button onclick="window.print()" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="advisorTable">
                        <thead class="table-light">
                            <tr>
                                <th>ชื่อกิจกรรม</th>
                                <th>วันที่จัด</th>
                                <th>สถานที่</th>
                                <th class="text-center">จำนวนคน</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($activities as $act): ?>
                            <tr>
                                <td><strong><?= $act['activity_name'] ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($act['start_date'])) ?></td>
                                <td><?= $act['location'] ?></td>
                                <td class="text-center"><span class="badge bg-info text-dark"><?= $act['participant_count'] ?> คน</span></td>
                                <td>
                                    <span class="badge <?= $act['status'] == 'approved' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= $act['status'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    // กราฟ
    const ctx = document.getElementById('advisorChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'จำนวนผู้เข้าร่วม (คน)',
                data: <?= json_encode($chart_values) ?>,
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: 'rgb(13, 110, 253)',
                borderWidth: 1
            }]
        }
    });

    // Export Excel
    function exportToExcel(tableID) {
        const table = document.getElementById(tableID);
        const wb = XLSX.utils.table_to_book(table, {sheet: "Activities"});
        XLSX.writeFile(wb, "Advisor_Report_Activities.xlsx");
    }
</script>

<style>
@media print {
    .sidebar, .btn-group, .card-header button { display: none !important; }
    .card { border: none !important; }
}
</style>

<?= $this->endSection() ?>