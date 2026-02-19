<?php namespace App\Controllers;

class Seed extends BaseController
{
    public function create_admin()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('admins');

        // ข้อมูล Admin ที่จะสร้าง
        $data = [
            'username' => 'admin',
            'password' => password_hash('1234', PASSWORD_DEFAULT), // รหัสผ่านคือ 1234
            'full_name' => 'ผู้ดูแลระบบ สูงสุด'
        ];

        // ตรวจสอบว่ามี user นี้หรือยัง
        if ($builder->where('username', 'admin')->countAllResults() == 0) {
            $builder->insert($data);
            echo "สร้าง Admin สำเร็จ! <br>User: admin <br>Pass: 1234";
        } else {
            echo "มี Admin คนนี้อยู่แล้ว";
        }
    }
}