<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommitteeSeeder extends Seeder
{
    public function run()
    {
        // ----------------------------------------------------
        // 1. เตรียมปีการศึกษา (2567)
        // ----------------------------------------------------
        $yearModel = new \App\Models\AcademicYearModel();
        $yearData = ['year_name' => '2567', 'is_current' => 1];
        
        $existYear = $yearModel->where('year_name', '2567')->first();
        $yearId = $existYear ? $existYear['year_id'] : $yearModel->insert($yearData);

        // ----------------------------------------------------
        // 2. เตรียมตำแหน่ง
        // ----------------------------------------------------
        $positionModel = new \App\Models\ClubPositionModel();
        $positions = ['นายกสโมสรนักศึกษา', 'รองนายกสโมสร', 'เลขานุการ', 'เหรัญญิก', 'กรรมการ'];
        $posIds = [];
        foreach ($positions as $posName) {
            $existPos = $positionModel->where('position_name', $posName)->first();
            $posIds[$posName] = $existPos ? $existPos['position_id'] : $positionModel->insert(['position_name' => $posName]);
        }

        $studentModel = new \App\Models\StudentModel();
        $committeeModel = new \App\Models\CommitteeModel();
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);

        // ----------------------------------------------------
        // 3. รายชื่อ 33 คน (ตัวจริงเท่านั้น)
        // ----------------------------------------------------
        $committeeNames = [
            'นายจิรกิตติ์ ตันตระกูล', 'นางสาวศุภณัฐ ปลื้มบุญ', 'นายณภัทร เหมือนเหลา',
            'นายภิรวัส พิกุลทอง', 'นายโชติกร สิตกรโกวิท', 'นางสาวภัทรชนก ทองโปรย',
            'นางสาวปุณยนุช ชัยวงศ์ฝั้น', 'นายอภิรักษ์ มีมุข', 'นายรชต อิ่มปรีชาวงศ์',
            'นางสาวฉัตรกวินท์ ยลเมือง', 'นายศักดิ์พงษ์ ธรรมขันธ์', 'นายไตรรัตน์ ลามี',
            'นายสรกฤช หล้าเมือง', 'นายพิทักษ์พงศ์ ยงกัน', 'นายฐิติกร ตันเพชร',
            'นางสาวภัชราพร แคลไหล', 'นางสาวยุพิน โนอิน', 'นางสาวสุภาพร พิมพา',
            'น.ส.เกศราพร เอมโอฐ', 'นายณรงค์ศักดิ์ คำน้อย', 'นายฐิติกร อัคผล',
            'นายชาญณรงค์ สมใจหวัง', 'นายวิรัชสัณห์ ประสิทธิ์พร', 'น.ส.ญาณภัทร นวลไม้หอม',
            'นายจิตติภณ จินดา', 'นางสาวนันทนา ทองผัง', 'นางสาวสุพรรษา มารอด',
            'นายกรกฎ ศุภหัตถี', 'นายธนภัทร โป๊ะโดย', 'น.ส.พัชรา เสน่ห์ราชกิจ',
            'นางสาวจิรนันท์ ล้อมแพน', 'นางสาวอรุณเนตร คงเมือง', 'นางสาวทิพวัลย์ สายันท์'
        ];

        echo "กำลังนำเข้าข้อมูล...\n";

        foreach ($committeeNames as $index => $fullName) {
            // รหัส 6600001 - 6600033
            $studentId = '66' . str_pad($index + 1, 5, '0', STR_PAD_LEFT); 
            
            // A. สร้างข้อมูลนักศึกษา (เฉพาะ 33 คนนี้)
            if (!$studentModel->find($studentId)) {
                $studentModel->insert([
                    'student_id'   => $studentId, 
                    'password'     => $passwordHash,
                    'full_name'    => $fullName, 
                    'major_id'     => 1, 
                    'phone_number' => '-'
                ]);
            }

            // B. แต่งตั้งตำแหน่ง
            if ($index == 0) $pos = 'นายกสโมสรนักศึกษา';
            elseif ($index == 1) $pos = 'รองนายกสโมสร';
            elseif ($index == 2) $pos = 'เลขานุการ';
            elseif ($index == 3) $pos = 'เหรัญญิก';
            else $pos = 'กรรมการ';

            if (!$committeeModel->where(['student_id' => $studentId, 'academic_year_id' => $yearId])->first()) {
                $committeeModel->insert([
                    'student_id' => $studentId, 
                    'academic_year_id' => $yearId, 
                    'position_id' => $posIds[$pos]
                ]);
            }
        }

        echo "✅ เสร็จสิ้น! มีเฉพาะกรรมการ 33 คนในระบบ (ไม่มีข้อมูลส่วนเกิน)";
    }
}