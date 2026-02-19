<?php namespace App\Controllers;

use App\Models\ActivityModel; // 1. เรียกใช้ Model กิจกรรม

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        
        // เช็คว่า Login หรือยัง? ถ้ายังให้ดีดกลับไปหน้า Login
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        // 2. ดึงข้อมูลกิจกรรมจากฐานข้อมูล
        $activityModel = new ActivityModel();
        
        // เลือกเฉพาะที่สถานะเป็น 'approved' (อนุมัติแล้ว) และเรียงตามวันที่เริ่ม
        $data['activities'] = $activityModel->where('status', 'approved')
                                            ->orderBy('start_date', 'ASC')
                                            ->findAll();

        // 3. ส่งข้อมูล $data ไปที่หน้า View
        return view('dashboard/index', $data);
    }
}