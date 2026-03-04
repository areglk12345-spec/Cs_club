<?= $this->extend('committee/layout') ?>

<?= $this->section('title') ?>รายงานสรุปผลกิจกรรม<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-chart-line"></i> รายงานสรุปผลการดำเนินงาน</h3>
        <div class="d-flex gap-2">
            <form action="<?= base_url('committee/reports') ?>" method="get" class="d-flex gap-2 align-items-center bg-white p-2 rounded shadow-sm border">
                <small class="text-muted fw-bold">ช่วงวันที่:</small>
                <input type="date" name="start_date" class="form-control form-control-sm" value="<?= $filters['start_date'] ?? '' ?>">
                <span>-</span>
                <input type="date" name="end_date" class="form-control form-control-sm" value="<?= $filters['end_date'] ?? '' ?>">
                <button type="submit" class="btn btn-primary btn-sm">ตกลง</button>
                <a href="<?= base_url('committee/reports') ?>" class="btn btn-outline-secondary btn-sm">ล้าง</a>
            </form>
            <button onclick="exportTableToExcel('detailTable')" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-title mb-0">กิจกรรมทั้งหมด</h6>
                        <h2 class="display-6 fw-bold"><?= $total_activities ?></h2>
                        <small>โครงการ</small>
                    </div>
                    <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-title mb-0">ผู้เข้าร่วมกิจกรรมรวม</h6>
                        <h2 class="display-6 fw-bold"><?= $total_participants ?></h2>
                        <small>คน (สะสม)</small>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-title mb-0">เฉลี่ยผู้เข้าร่วม</h6>
                        <h2 class="display-6 fw-bold">
                            <?= ($total_activities > 0) ? number_format($total_participants / $total_activities, 1) : 0 ?>
                        </h2>
                        <small>คน / กิจกรรม</small>
                    </div>
                    <i class="fas fa-chart-pie fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-chart-bar"></i> จำนวนผู้เข้าร่วมแต่ละกิจกรรม</h6>
                </div>
                <div class="card-body">
                    <canvas id="barChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie"></i> สัดส่วนสาขาวิชา</h6>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-trophy"></i> 5 อันดับนักกิจกรรมตัวยง (Hall of Fame)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%">อันดับ</th>
                                    <th>รหัสนักศึกษา</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>สาขาวิชา</th>
                                    <th class="text-center">จำนวนกิจกรรมที่เข้า</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($top_students)): ?>
                                    <?php foreach ($top_students as $index => $student): ?>
                                        <tr>
                                            <td class="text-center fw-bold">
                                                <?php if ($index == 0): ?> 🥇
                                                <?php elseif ($index == 1): ?> 🥈
                                                <?php elseif ($index == 2): ?> 🥉
                                                <?php else: ?>             <?= $index + 1 ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $student['student_id'] ?></td>
                                            <td><?= $student['full_name'] ?></td>
                                            <td><?= $student['major_name'] ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-danger rounded-pill px-3"><?= $student['join_count'] ?>
                                                    ครั้ง</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">ยังไม่มีข้อมูลการเข้าร่วม</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold"><i class="fas fa-list"></i> รายละเอียดกิจกรรมทั้งหมด</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="detailTable">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อกิจกรรม</th>
                            <th class="text-center">เรตติ้ง</th>
                            <th class="text-center">ประเมิน</th>
                            <th>วันที่จัด</th>
                            <th>สถานที่</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activities_list as $a): ?>
                            <tr>
                                <td><?= esc($a['activity_name']) ?></td>
                                <td class="text-center">
                                    <?php if(isset($feedback_summary[$a['activity_id']])): ?>
                                        <span class="text-warning fw-bold"><?= number_format($feedback_summary[$a['activity_id']]['avg_rating'], 1) ?></span>
                                        <small class="text-muted">/ 5</small>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">
                                        <?= $feedback_summary[$a['activity_id']]['feedback_count'] ?? 0 ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($a['start_date'])) ?></td>
                                <td><?= esc($a['location']) ?></td>
                                <td>
                                    <?php
                                    $badge = match ($a['status']) {
                                        'completed' => 'success',
                                        'open' => 'primary',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                    $text = match ($a['status']) {
                                        'completed' => 'เสร็จสิ้น',
                                        'open' => 'เปิดรับสมัคร',
                                        'cancelled' => 'ยกเลิก',
                                        default => 'ปิด/อื่นๆ'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= $text ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    // ข้อมูลสำหรับกราฟแท่ง
    const barData = <?= json_encode($bar_chart_data) ?>;
    const barLabels = barData.map(item => item.activity_name);
    const barValues = barData.map(item => item.student_count);

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                label: 'จำนวนผู้เข้าร่วม (คน)',
                data: barValues,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // ข้อมูลสำหรับกราฟวงกลม
    const pieData = <?= json_encode($pie_chart_data) ?>;
    const pieLabels = pieData.map(item => item.major_name);
    const pieValues = pieData.map(item => item.count);
    const bgColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieValues,
                backgroundColor: bgColors,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // ฟังก์ชัน Export Excel
    function exportTableToExcel(tableID, filename = 'report_activity') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
</script>
<?= $this->endSection() ?>