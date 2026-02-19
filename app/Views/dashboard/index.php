<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>หน้าหลักนักศึกษา<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-primary shadow-sm d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="alert-heading mb-1"><i class="fas fa-smile-beam"></i> ยินดีต้อนรับ!</h4>
                    <p class="mb-0">
                        สวัสดีคุณ <strong><?= session()->get('full_name') ?></strong> 
                        <span class="badge bg-light text-primary ms-2">
                            <?= session()->get('student_id') ?>
                        </span>
                    </p>
                </div>
                <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('ยืนยันออกจากระบบ?');">
                    <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <div class="card text-center mb-3 shadow-sm border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-bullhorn fa-2x mb-2"></i><br>กิจกรรมที่เปิดรับ
                    </h5>
                    <p class="card-text small text-muted">ดูรายชื่อกิจกรรมและลงทะเบียนเข้าร่วม</p>
                    <a href="#activity-list" class="btn btn-outline-primary w-100 stretched-link">ดูรายการ</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-center mb-3 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-info">
                        <i class="fas fa-history fa-2x mb-2"></i><br>ประวัติกิจกรรม
                    </h5>
                    <p class="card-text small text-muted">ตรวจสอบกิจกรรมที่คุณเคยเข้าร่วม</p>
                    <a href="<?= base_url('student/history') ?>" class="btn btn-outline-info w-100">ดูประวัติ</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
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
        
        <?php if(empty($activities)): ?>
            <div class="alert alert-secondary text-center p-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5>ยังไม่มีกิจกรรมที่เปิดรับสมัครในขณะนี้</h5>
                <p>โปรดติดตามประกาศจากสโมสรนักศึกษาในภายหลัง</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($activities as $act): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-start border-success border-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title text-success fw-bold"><?= $act['activity_name'] ?></h5>
                                <span class="badge bg-success">เปิดรับสมัคร</span>
                            </div>
                            <p class="card-text text-muted small mb-2">
                                <?= mb_substr($act['description'], 0, 100) ?>...
                            </p>
                            
                            <ul class="list-unstyled small mt-3">
                                <li class="mb-1"><i class="fas fa-clock text-warning"></i> <strong>เริ่ม:</strong> <?= date('d/m/Y H:i', strtotime($act['start_date'])) ?></li>
                                <li class="mb-1"><i class="fas fa-map-marker-alt text-danger"></i> <strong>สถานที่:</strong> <?= $act['location'] ?></li>
                            </ul>
                            
                            <a href="<?= base_url('student/activity/detail/'.$act['activity_id']) ?>" class="btn btn-primary w-100 mt-2">
                                <i class="fas fa-info-circle"></i> ดูรายละเอียด & สมัคร
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>