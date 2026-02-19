<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>แก้ไขข้อมูลส่วนตัว<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">แก้ไขข้อมูลส่วนตัว</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> แก้ไขข้อมูลส่วนตัว</h5>
                </div>
                <div class="card-body">
                    
                    <form action="<?= base_url('student/profile/update') ?>" method="post">
                        
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">รหัสนักศึกษา</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control-plaintext" value="<?= $student['student_id'] ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">ชื่อ-นามสกุล</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control-plaintext" value="<?= $student['full_name'] ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">เบอร์โทรศัพท์</label>
                            <div class="col-sm-9">
                                <input type="text" name="phone_number" class="form-control" value="<?= $student['phone_number'] ?>" required>
                            </div>
                        </div>

                        <hr>
                        <p class="text-muted small">เปลี่ยนรหัสผ่าน (ถ้าไม่เปลี่ยนให้เว้นว่างไว้)</p>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">รหัสผ่านใหม่</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control" placeholder="กรอกรหัสผ่านใหม่...">
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('ยืนยันการแก้ไขข้อมูล?');">บันทึกข้อมูล</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>