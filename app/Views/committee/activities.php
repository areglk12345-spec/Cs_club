<?= $this->extend('committee/layout') ?>
<?= $this->section('title') ?>จัดการกิจกรรม<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-calendar-alt"></i> จัดการกิจกรรม</h3>
    <a href="<?= base_url('committee/activity/create') ?>" class="btn btn-success">
        <i class="fas fa-plus"></i> เพิ่มกิจกรรมใหม่
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ชื่อกิจกรรม</th>
                    <th>วันที่จัด</th>
                    <th>สถานที่</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($activities as $act): ?>
                <tr>
                    <td><?= $act['activity_name'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($act['start_date'])) ?></td>
                    <td><?= $act['location'] ?></td>
                    <td>
    <?php 
        $status = $act['status'];
        $badgeClass = 'bg-secondary';
        $statusText = $status;

        switch ($status) {
            case 'planning':
                $badgeClass = 'bg-warning text-dark';
                $statusText = 'รออนุมัติ';
                break;
            case 'approved':
                $badgeClass = 'bg-success';
                $statusText = 'เปิดรับสมัคร';
                break;
            case 'completed':  // ✅ ตรงนี้คือสถานะ ปิดรับสมัคร/เสร็จสิ้น
                $badgeClass = 'bg-secondary';
                $statusText = 'ปิดรับสมัคร/เสร็จสิ้น';
                break;
            case 'cancelled':
                $badgeClass = 'bg-dark';
                $statusText = 'ยกเลิก';
                break;
            case 'rejected':
                $badgeClass = 'bg-danger';
                $statusText = 'ไม่ผ่านอนุมัติ';
                break;
            default:
                // กรณีข้อมูลผิดพลาด หรือเป็นค่าว่าง
                $statusText = ($status == '') ? 'ระบุสถานะผิดพลาด' : $status;
        }
    ?>
    <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
</td>
                    <td>
    <a href="<?= base_url('committee/activity/edit/'.$act['activity_id']) ?>" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i> แก้ไข
    </a>
    <a href="<?= base_url('committee/activity/delete/'.$act['activity_id']) ?>" 
       class="btn btn-danger btn-sm"
       onclick="return confirm('ยืนยันที่จะลบกิจกรรมนี้? ข้อมูลการสมัครจะหายไปทั้งหมด');">
        <i class="fas fa-trash"></i> ลบ
    </a>
</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>