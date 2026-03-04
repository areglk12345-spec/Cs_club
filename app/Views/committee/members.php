<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>รายชื่อคณะกรรมการ<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-primary">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0"><i class="fas fa-user-tie"></i> รายชื่อคณะกรรมการสโมสรนักศึกษา</h5>
        <span class="badge bg-light text-primary"><?= count($students) ?> ท่าน</span>
    </div>
    <div class="card-body">

        <!-- Filter Form -->
        <form action="<?= base_url('committee/members') ?>" method="get" class="row g-2 mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="ชื่อ-นามสกุล หรือ รหัสนักศึกษา..." value="<?= $filters['search'] ?? '' ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="major_id" class="form-select bg-light">
                    <option value="">-- ทุกสาขาวิชา --</option>
                    <?php foreach ($majors as $m): ?>
                        <option value="<?= $m['major_id'] ?>" <?= ($filters['major_id'] == $m['major_id']) ? 'selected' : '' ?>><?= $m['major_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="position_id" class="form-select bg-light">
                    <option value="">-- ทุกตำแหน่ง --</option>
                    <?php foreach ($positions as $p): ?>
                        <option value="<?= $p['position_id'] ?>" <?= ($filters['position_id'] == $p['position_id']) ? 'selected' : '' ?>><?= $p['position_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> กรอง</button>
            </div>
        </form>

        <div class="table-responsive border rounded">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">รหัสนักศึกษา</th>
                        <th width="20%">ชื่อ-นามสกุล</th>
                        <th width="20%">สาขาวิชา</th>
                        <th width="20%">ตำแหน่ง</th>
                        <th width="20%">เบอร์โทรศัพท์</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">ไม่พบข้อมูลคณะกรรมการ</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $index => $std): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $std['student_id'] ?></td>
                                <td><?= $std['full_name'] ?></td>
                                <td class="small"><?= $std['major_name'] ?></td>
                                <td>
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill small">
                                        <i class="fas fa-star"></i> <?= $std['position_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $std['phone_number'] ?? '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>