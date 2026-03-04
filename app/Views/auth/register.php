<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>สมัครสมาชิก<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus"></i> สมัครสมาชิก (นักศึกษา)</h4>
                </div>
                <div class="card-body">

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="<?= base_url('register/save') ?>" method="post">
                        
                        <div class="mb-3">
                            <label class="form-label">รหัสนักศึกษา <span class="text-danger">*</span></label>
                            <input type="text" name="student_id" class="form-control" 
                                   value="<?= old('student_id') ?>" placeholder="รหัสนักศึกษา" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" 
                                   value="<?= old('full_name') ?>" placeholder="ชื่อ-สกุล ภาษาไทย" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">สาขาวิชา <span class="text-danger">*</span></label>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= old('email') ?>" placeholder="example@email.com" required>
                            <small class="text-muted" style="font-size: 0.8rem;">ใช้สำหรับรับแจ้งเตือนกิจกรรม</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone_number" class="form-control" 
                                   value="<?= old('phone_number') ?>" placeholder="เบอร์โทรศัพท์">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                                <input type="password" name="pass_confirm" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">ลงทะเบียน</button>
                            <div class="text-center mt-2">
                                <a href="<?= base_url('login') ?>" class="small">กลับไปหน้าเข้าสู่ระบบ</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>