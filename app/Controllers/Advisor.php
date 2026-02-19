<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\RegistrationModel;

class Advisor extends BaseController
{
    // เช็คสิทธิ์การเข้าใช้งาน (ตัวอย่างเบื้องต้น)
    // ไฟล์: app/Controllers/Advisor.php

private function checkAuth()
{
    // ✅ แก้จาก 'isLoggedIn' เป็น 'is_logged_in' ให้ตรงกับ Auth.php
    if (!session()->get('is_logged_in') || session()->get('role') !== 'advisor') {
        return false;
    }
    return true;
}

    public function dashboard()
{
    if (!$this->checkAuth()) return redirect()->to('login');
    
    $db = \Config\Database::connect();
    $activityModel = new \App\Models\ActivityModel();

    // 1. ข้อมูลสรุปบน Card
    $data['pending_count'] = $activityModel->where('status', 'planning')->countAllResults();
    $data['total_activities'] = $activityModel->countAllResults();
    $data['total_students'] = $db->table('students')->countAll();

    // 2. ข้อมูลสำหรับกราฟ (Chart.js)
    $chartData = $db->table('activities')
        ->select('activities.activity_name, COUNT(activity_registrations.registration_id) as total')
        ->join('activity_registrations', 'activity_registrations.activity_id = activities.activity_id', 'left')
        ->groupBy('activities.activity_id')
        ->limit(6)
        ->get()->getResultArray();

    $data['chart_labels'] = array_column($chartData, 'activity_name');
    $data['chart_values'] = array_column($chartData, 'total');

    // 3. ข้อมูลแบบตารางเดิม
    $data['activities'] = $db->table('activities')
        ->select('activities.*, COUNT(activity_registrations.registration_id) as participant_count')
        ->join('activity_registrations', 'activity_registrations.activity_id = activities.activity_id', 'left')
        ->groupBy('activities.activity_id')
        ->orderBy('activities.start_date', 'DESC')
        ->get()->getResultArray();

    return view('advisor/dashboard', $data);
}

    // --- 1.3.3.1 ตรวจสอบกิจกรรม (อนุมัติกิจกรรม) ---
    public function check_activities()
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $activityModel = new ActivityModel();
        // ดึงกิจกรรมทั้งหมด โดยเรียงเอาที่รออนุมัติ (planning) ขึ้นก่อน
        $data['activities'] = $activityModel->orderBy('status', 'DESC')->findAll();
        
        return view('advisor/check_activities', $data);
    }

    public function approve_activity($id)
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $activityModel = new ActivityModel();
        $activityModel->update($id, ['status' => 'approved']);
        
        return redirect()->to('advisor/check_activities')->with('success', 'อนุมัติกิจกรรมเรียบร้อยแล้ว นักศึกษาสามารถมองเห็นกิจกรรมนี้ได้แล้ว');
    }

    // --- 1.3.3.2 รายงานที่เกี่ยวข้องกับอาจารย์ ---
    public function reports()
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $db = \Config\Database::connect();
        // รายงานการดำเนินกิจกรรมทั้งหมดและจำนวนคนเข้าร่วม
        $data['activities'] = $db->table('activities')
            ->select('activities.*, COUNT(activity_registrations.registration_id) as participant_count')
            ->join('activity_registrations', 'activity_registrations.activity_id = activities.activity_id', 'left')
            ->groupBy('activities.activity_id')
            ->get()->getResultArray();

        return view('advisor/reports', $data);
    }

    // --- 1.3.3.2 รายงานรายชื่อนักศึกษาที่เข้าร่วมกิจกรรม ---
    public function report_participants($activityId)
    {
        if (!$this->checkAuth()) return redirect()->to('login');

        $db = \Config\Database::connect();
        $activityModel = new \App\Models\ActivityModel();
        
        // 1. ดึงข้อมูลกิจกรรมเพื่อเอาชื่อมาโชว์ในหัวข้อ
        $data['activity'] = $activityModel->find($activityId);
        
        if (!$data['activity']) {
            return redirect()->to('advisor/reports')->with('msg', 'ไม่พบข้อมูลกิจกรรม');
        }

        // 2. ดึงรายชื่อนักศึกษาที่สมัครและได้รับการอนุมัติ (status = approved)
        $data['participants'] = $db->table('activity_registrations')
            ->select('activity_registrations.*, students.full_name, students.student_id as std_id, majors.major_name')
            ->join('students', 'students.student_id = activity_registrations.student_id')
            ->join('majors', 'majors.major_id = students.major_id', 'left') // Join สาขาเพื่อให้ข้อมูลครบ
            ->where('activity_registrations.activity_id', $activityId)
            ->where('activity_registrations.status', 'approved') // นับเฉพาะคนที่ผ่านการเช็คชื่อ/อนุมัติแล้ว
            ->orderBy('students.student_id', 'ASC')
            ->get()
            ->getResultArray();

        return view('advisor/report_participants', $data);
    }
    public function reject_activity($id)
{
    if (!$this->checkAuth()) return redirect()->to('login');
    
    $model = new \App\Models\ActivityModel();
    $model->update($id, ['status' => 'rejected']);
    
    return redirect()->to('advisor/check_activities')->with('error', 'ปฏิเสธกิจกรรมเรียบร้อยแล้ว สถานะถูกเปลี่ยนเป็น "ไม่ผ่านอนุมัติ"');
}
}