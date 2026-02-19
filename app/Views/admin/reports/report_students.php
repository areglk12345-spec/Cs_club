<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานข้อมูลนักศึกษา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 14px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="p-4">

    <div class="container">
        <div class="text-end mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> พิมพ์รายงาน</button>
            <a href="<?= base_url('admin/reports') ?>" class="btn btn-secondary">ย้อนกลับ</a>
        </div>

        <div class="text-center mb-4">
            <h3>รายงานข้อมูลนักศึกษาทั้งหมด</h3>
            <p>ข้อมูล ณ วันที่: <?= $print_date ?></p>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 15%">รหัสนักศึกษา</th>
                    <th style="width: 30%">ชื่อ-นามสกุล</th>
                    <th style="width: 25%">สาขาวิชา</th>
                    <th style="width: 15%">เบอร์โทรศัพท์</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($students)): ?>
                    <tr><td colspan="5" class="text-center">ไม่พบข้อมูล</td></tr>
                <?php else: ?>
                    <?php foreach ($students as $index => $row) : ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td><?= $row['student_id'] ?></td>
                        <td><?= $row['full_name'] ?></td>
                        <td><?= $row['major_name'] ?></td>
                        <td><?= $row['phone_number'] ?></td>
                    </tr>
                    <?php endforeach ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="text-end mt-4">
            <small class="text-muted">ผู้ออกรายงาน: ผู้ดูแลระบบ</small>
        </div>
    </div>

</body>
</html>