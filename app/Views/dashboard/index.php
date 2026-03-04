<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>หน้าหลักนักศึกษา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-primary shadow-sm">
                <h4 class="alert-heading mb-1"><i class="fas fa-smile-beam"></i> ยินดีต้อนรับ!</h4>
                <p class="mb-0">
                    สวัสดีคุณ <strong><?= session()->get('full_name') ?></strong>
                    <span class="badge bg-light text-primary ms-2">
                        <?= session()->get('student_id') ?>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card text-center mb-3 shadow-sm border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-bullhorn fa-2x mb-2"></i><br>กิจกรรมที่เปิดรับ
                    </h5>
                    <p class="card-text small text-muted">ดูรายชื่อกิจกรรมและลงทะเบียน</p>
                    <a href="#activity-list" class="btn btn-outline-primary w-100 stretched-link">ดูรายการ</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center mb-3 shadow-sm h-100 border-success shadow-on-hover" style="cursor: pointer;"
                data-bs-toggle="modal" data-bs-target="#scanModal">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        <i class="fas fa-qrcode fa-2x mb-2"></i><br>สแกนเช็คชื่อ
                    </h5>
                    <p class="card-text small text-muted">สแกน QR เพื่อบันทึกการเข้าร่วม</p>
                    <button class="btn btn-success w-100">เปิดกล้อง</button>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center mb-3 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-info">
                        <i class="fas fa-history fa-2x mb-2"></i><br>ประวัติกิจกรรม
                    </h5>
                    <p class="card-text small text-muted">ตรวจสอบกิจกรรมที่เข้าร่วม</p>
                    <a href="<?= base_url('student/history') ?>" class="btn btn-outline-info w-100">ดูประวัติ</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center mb-3 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-user-cog fa-2x mb-2"></i><br>ข้อมูลส่วนตัว
                    </h5>
                    <p class="card-text small text-muted">แก้ไขเบอร์โทรหรือรหัสผ่าน</p>
                    <a href="<?= base_url('student/profile') ?>" class="btn btn-outline-warning w-100">แก้ไขข้อมูล</a>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div id="activity-list">
        <h4 class="mb-3"><i class="fas fa-calendar-alt text-success"></i> กิจกรรมที่กำลังเปิดรับสมัคร</h4>

        <?php if (empty($activities)): ?>
            <div class="alert alert-secondary text-center p-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5>ยังไม่มีกิจกรรมที่เปิดรับสมัครในขณะนี้</h5>
                <p>โปรดติดตามประกาศจากสโมสรนักศึกษาในภายหลัง</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($activities as $act): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <!-- รูปปกกิจกรรม -->
                            <div style="height: 180px; overflow: hidden; background: #eee;">
                                <?php if (!empty($act['cover_image'])): ?>
                                    <img src="<?= base_url('uploads/activities/' . $act['cover_image']) ?>" class="card-img-top"
                                        style="object-fit: cover; height: 100%;">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                        <i class="fas fa-image fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title text-success fw-bold"><?= $act['activity_name'] ?></h5>
                                    <?php if (isset($reg_status_map[$act['activity_id']])): ?>
                                        <?php
                                        $regStatus = $reg_status_map[$act['activity_id']];
                                        $badgeClass = ($regStatus == 'approved') ? 'bg-info' : (($regStatus == 'rejected') ? 'bg-danger' : 'bg-warning');
                                        $statusText = ($regStatus == 'approved') ? 'อนุมัติแล้ว' : (($regStatus == 'rejected') ? 'ไม่ผ่าน' : 'รออนุมัติ');
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success">เปิดรับสมัคร</span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text text-muted small mb-2">
                                    <?= mb_substr($act['description'], 0, 100) ?>...
                                </p>

                                <ul class="list-unstyled small mt-3">
                                    <li class="mb-1"><i class="fas fa-clock text-warning"></i> <strong>เริ่ม:</strong>
                                        <?= date('d/m/Y H:i', strtotime($act['start_date'])) ?></li>
                                    <li class="mb-1"><i class="fas fa-map-marker-alt text-danger"></i> <strong>สถานที่:</strong>
                                        <?= $act['location'] ?></li>
                                </ul>

                                <?php if (isset($reg_status_map[$act['activity_id']])): ?>
                                    <a href="<?= base_url('student/activity/detail/' . $act['activity_id']) ?>"
                                        class="btn btn-outline-primary w-100 mt-2">
                                        <i class="fas fa-search"></i> ดูสถานะการสมัคร
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('student/activity/detail/' . $act['activity_id']) ?>"
                                        class="btn btn-primary w-100 mt-2">
                                        <i class="fas fa-info-circle"></i> ดูรายละเอียด & สมัคร
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Modal สำหรับการสแกน QR Code -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-camera"></i> สแกน QR Code เช็คชื่อ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                    id="stopScan"></button>
            </div>
            <div class="modal-body p-0">
                <div id="reader" style="width: 100%; min-height: 300px; background: #000;"></div>
                <div class="p-3 text-center bg-light">
                    <p class="text-muted small mb-0">กรุณาวาง QR Code ให้ตรงกับกรอบสแกน</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const html5QrCode = new Html5Qrcode("reader");
        const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

        const scanModal = document.getElementById('scanModal');
        scanModal.addEventListener('shown.bs.modal', function () {
            html5QrCode.start(
                { facingMode: "environment" },
                qrConfig,
                (decodedText, decodedResult) => {
                    // หยุดสแกนเมื่อเจอ QR
                    html5QrCode.stop().then(() => {
                        // ไปที่ URL ที่สแกนได้ (ตรวจสอบเบื้องต้นว่าเป็นของระบบเรา)
                        if (decodedText.includes('<?= base_url('scan/') ?>')) {
                            window.location.href = decodedText;
                        } else {
                            alert('QR Code ไม่ถูกต้องสำหรับระบบนี้');
                            location.reload();
                        }
                    });
                },
                (errorMessage) => {
                    // error ระหว่างสแกน (ข้ามไปได้)
                }
            ).catch((err) => {
                console.error(err);
                alert("ไม่สามารถเข้าถึงกล้องได้: " + err);
            });
        });

        // หยุดกล้องเมื่อปิด Modal
        document.getElementById('stopScan').addEventListener('click', stopCamera);
        scanModal.addEventListener('hidden.bs.modal', stopCamera);

        function stopCamera() {
            if (html5QrCode.getState() === 2) { // 2 = SCANNING
                html5QrCode.stop();
            }
        }
    });
</script>

<style>
    .shadow-on-hover {
        transition: all 0.3s ease;
    }

    .shadow-on-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }
</style>

<?= $this->endSection() ?>