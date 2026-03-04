<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>เข้าสู่ระบบ<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card mt-5 shadow-sm">
            <div class="card-header text-center border-bottom-0 pt-4">
                <h4 class="mb-1">เข้าสู่ระบบ</h4>
                <p class="small opacity-75">ระบบจัดการข้อมูลสโมสรนักศึกษา</p>
            </div>
            <div class="card-body p-4">

                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="alert alert-danger text-center mb-3 py-2 small">
                        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success text-center mb-3 py-2 small">
                        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/process') ?>" method="post">

                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้งาน / รหัสนักศึกษา</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="กรอกรหัสนักศึกษา" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="กรอกรหัสผ่าน" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">สถานะผู้ใช้งาน</label>
                        <select class="form-select" name="role">
                            <option value="student">นักศึกษา</option>
                            <option value="committee">คณะกรรมการสโมสร</option>
                            <option value="advisor">อาจารย์ที่ปรึกษา</option>
                            <option value="admin">ผู้ดูแลระบบ</option>
                        </select>
                    </div>


                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">เข้าสู่ระบบ</button>
                    </div>
                </form>

            </div>
            <div class="card-footer text-center py-3">
                <p class="mb-0 small opacity-75">ยังไม่มีบัญชี? <a href="<?= base_url('register') ?>"
                        class="fw-bold">ลงทะเบียนสำหรับนักศึกษา</a></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>