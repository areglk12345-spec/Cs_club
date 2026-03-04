<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>ผู้ดูแลระบบ<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="alert alert-primary shadow-sm">
                <h4><i class="fas fa-user-shield"></i> ผู้ดูแลระบบ (Admin)</h4>
                <p class="mb-0">จัดการข้อมูลพื้นฐานของระบบ</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-user-graduate fa-2x mb-2"></i>
                    <h6 class="card-title mb-1">นักศึกษาทั้งหมด</h6>
                    <h3 class="fw-bold mb-0"><?= number_format($total_students) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-tasks fa-2x mb-2"></i>
                    <h6 class="card-title mb-1">กิจกรรมทั้งหมด</h6>
                    <h3 class="fw-bold mb-0"><?= number_format($total_activities) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-users-cog fa-2x mb-2"></i>
                    <h6 class="card-title mb-1">คณะกรรมการ</h6>
                    <h3 class="fw-bold mb-0"><?= number_format($total_committee) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-university fa-2x mb-2"></i>
                    <h6 class="card-title mb-1">สาขาวิชา</h6>
                    <h3 class="fw-bold mb-0"><?= number_format($total_majors) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary"><i class="fas fa-chart-line"></i> สถิติการสมัครเข้าร่วมกิจกรรม (8
                        อันดับแรก)</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="adminStatsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- เมนูจัดการข้อมูลเดิม -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-university fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">จัดการข้อมูลสาขา</h5>
                    <a href="<?= base_url('admin/majors') ?>" class="btn btn-outline-primary w-100">จัดการ</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-users-cog fa-3x text-success mb-3"></i>
                    <h5 class="card-title">จัดการกรรมการสโมสร</h5>
                    <a href="<?= base_url('admin/committee') ?>" class="btn btn-outline-success w-100">จัดการ</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x text-info mb-3"></i>
                    <h5 class="card-title">จัดการปีการศึกษา</h5>
                    <a href="<?= base_url('admin/years') ?>" class="btn btn-outline-info w-100">จัดการ</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-id-badge fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">จัดการตำแหน่ง</h5>
                    <a href="<?= base_url('admin/positions') ?>" class="btn btn-outline-warning w-100">จัดการ</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-user-graduate fa-3x text-dark mb-3"></i>
                    <h5 class="card-title">จัดการข้อมูลนักศึกษา</h5>
                    <a href="<?= base_url('admin/students') ?>" class="btn btn-outline-dark w-100">จัดการ</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-teacher fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">จัดการอาจารย์ที่ปรึกษา</h5>
                    <a href="<?= base_url('admin/advisors') ?>" class="btn btn-outline-danger w-100">จัดการ</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                    <h5 class="card-title">รายงานระบบ</h5>
                    <a href="<?= base_url('admin/reports') ?>" class="btn btn-outline-secondary w-100">เลือกดูรายงาน</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let statsChart;
        function initChart() {
            const ctx = document.getElementById('adminStatsChart').getContext('2d');
            const themeConfig = window.getChartColors ? window.getChartColors() : { text: '#666', grid: '#eee' };

            if (statsChart) statsChart.destroy();

            statsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($chart_labels) ?>,
                    datasets: [{
                        label: 'จำนวนผู้สมัคร (คน)',
                        data: <?= json_encode($chart_values) ?>,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: themeConfig.grid },
                            ticks: { color: themeConfig.text }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: themeConfig.text }
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initChart);
        window.addEventListener('themeChanged', initChart);
    </script>
</div>
<?= $this->endSection() ?>