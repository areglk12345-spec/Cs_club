<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ActivityModel;
use App\Models\RegistrationModel;

class Student extends BaseController
{
    // -------------------------------------------------------------------------
    //  หน้า Dashboard (รายการกิจกรรม)
    // -------------------------------------------------------------------------
    public function index()
    {
        $session = session();

        // 1. เช็ค Login
        if (!$session->get('is_logged_in')) {
            return redirect()->to('login')->with('msg', 'กรุณาเข้าสู่ระบบก่อน');
        }

        // 2. ถ้าเป็น Admin ให้ดีดไปหน้า Admin
        if ($session->get('role') == 'admin') {
            return redirect()->to('admin/dashboard');
        }

        // 3. ดึงกิจกรรมที่อนุมัติแล้ว
        $activityModel = new ActivityModel();
        $data['activities'] = $activityModel->where('status', 'approved')
                                            ->orderBy('start_date', 'ASC')
                                            ->findAll();

        return view('dashboard/index', $data);
    }

    // -------------------------------------------------------------------------
    //  หน้าดูรายละเอียดกิจกรรม
    // -------------------------------------------------------------------------
    public function activity_detail($id)
    {
        $activityModel = new ActivityModel();
        $regModel = new RegistrationModel();
        $studentId = session()->get('student_id');

        // 1. ดึงข้อมูลกิจกรรม
        $data['activity'] = $activityModel->find($id);
        
        if (!$data['activity']) {
            return redirect()->to('dashboard')->with('error', 'ไม่พบกิจกรรม');
        }

        // 2. เช็คประวัติการสมัคร (เพื่อซ่อนปุ่มสมัคร)
        $exists = $regModel->where('activity_id', $id)
                           ->where('student_id', $studentId)
                           ->first();

        $data['is_registered'] = ($exists) ? true : false;
        $data['reg_status'] = ($exists) ? $exists['status'] : null;

        return view('student/activity_detail', $data);
    }

    // -------------------------------------------------------------------------
    //  ฟังก์ชันบันทึกการสมัคร
    // -------------------------------------------------------------------------
    public function register_activity()
    {
        $regModel = new RegistrationModel();
        $studentId  = session()->get('student_id');
        $activityId = $this->request->getPost('activity_id');

        // 1. กันการสมัครซ้ำ
        $exists = $regModel->where('activity_id', $activityId)
                           ->where('student_id', $studentId)
                           ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'คุณได้ลงชื่อเข้าร่วมกิจกรรมนี้ไปแล้ว');
        }

        // 2. บันทึกข้อมูล
        $regModel->save([
            'activity_id' => $activityId,
            'student_id'  => $studentId,
            'register_date' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        return redirect()->to('dashboard')->with('success', 'สมัครเข้าร่วมกิจกรรมสำเร็จแล้ว!');
    }

    // -------------------------------------------------------------------------
    //  ✅ ฟังก์ชันประวัติกิจกรรม (ที่หายไป)
    // -------------------------------------------------------------------------
    public function history()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('login');
        }

        $studentId = session()->get('student_id');
        $db = \Config\Database::connect();

        // Join ตารางเพื่อให้ได้ชื่อกิจกรรม
        $data['history'] = $db->table('activity_registrations')
                              ->join('activities', 'activities.activity_id = activity_registrations.activity_id')
                              ->where('activity_registrations.student_id', $studentId)
                              ->orderBy('activity_registrations.register_date', 'DESC')
                              ->get()
                              ->getResultArray();

        return view('student/history', $data);
    }

    // ... (ต่อจากฟังก์ชัน history)

    // -------------------------------------------------------------------------
    //  หน้าแก้ไขข้อมูลส่วนตัว
    // -------------------------------------------------------------------------
    public function profile()
    {
        if (!session()->get('is_logged_in')) return redirect()->to('login');

        $model = new \App\Models\StudentModel();
        // ดึงข้อมูลนักศึกษาปัจจุบัน
        $data['student'] = $model->find(session()->get('student_id'));

        return view('student/profile', $data);
    }

    public function update_profile()
    {
        if (!session()->get('is_logged_in')) return redirect()->to('login');

        $model = new \App\Models\StudentModel();
        $id = session()->get('student_id');

        $data = [
            'phone_number' => $this->request->getPost('phone_number'),
        ];

        // ถ้ามีการกรอกรหัสผ่านใหม่ ให้เปลี่ยนด้วย
        $newPass = $this->request->getPost('password');
        if (!empty($newPass)) {
            $data['password'] = password_hash($newPass, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);

        return redirect()->to('dashboard')->with('success', 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }
}