<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานสมาชิกสโมสร</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 14px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="p-4">

    <div class="container">
        <div class="text-end mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary">พิมพ์รายงาน</button>
            <a href="<?= base_url('admin/reports') ?>" class="btn btn-secondary">ย้อนกลับ</a>
        </div>

        <div class="text-center mb-4">
            <h3>ทำเนียบสมาชิกสโมสรนักศึกษา (คณะกรรมการ)</h3>
            <p>ข้อมูล ณ วันที่: <?= $print_date ?></p>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ปีการศึกษา</th>
                    <th>ตำแหน่ง</th>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ-นามสกุล</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($committee)): ?>
                    <tr><td colspan="4" class="text-center">ไม่พบข้อมูล</td></tr>
                <?php else: ?>
                    <?php foreach ($committee as $row) : ?>
                    <tr>
                        <td class="text-center fw-bold"><?= $row['year_name'] ?></td>
                        <td><?= $row['position_name'] ?></td>
                        <td><?= $row['student_id'] ?></td>
                        <td><?= $row['full_name'] ?></td>
                    </tr>
                    <?php endforeach ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>