<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $year = $db->table('academic_years')->where('year_name', '2567')->get()->getRow();
        $yearId = $year ? $year->year_id : 1;

        $activities = [
            [
                'activity_name'    => 'โครงการรับน้องใหม่ สานสัมพันธ์พี่น้อง',
                'description'      => 'กิจกรรมสันทนาการเพื่อต้อนรับนักศึกษาใหม่', // ✅ ใช้ description
                'start_date'       => '2026-06-15 09:00:00', // ✅ ใช้ start_date
                'end_date'         => '2026-06-15 16:00:00',
                'location'         => 'หอประชุมใหญ่ อาคาร 1',
                'academic_year_id' => $yearId,
                'status'           => 'approved' // ✅ ใช้ค่าที่มีใน Enum ของคุณ
            ],
            [
                'activity_name'    => 'Workshop CodeIgniter 4',
                'description'      => 'อบรมการเขียนโปรแกรมเว็บเบื้องต้น',
                'start_date'       => '2026-07-10 13:00:00',
                'end_date'         => '2026-07-10 17:00:00',
                'location'         => 'ห้องคอมพิวเตอร์ 402',
                'academic_year_id' => $yearId,
                'status'           => 'approved'
            ]
        ];

        $db->table('activities')->insertBatch($activities);
        echo "✅ สร้างกิจกรรมตัวอย่างเรียบร้อยแล้ว!";
    }
}