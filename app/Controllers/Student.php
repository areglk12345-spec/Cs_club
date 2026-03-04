<?php
namespace App\Controllers;

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
        $regModel = new RegistrationModel();
        $studentId = $session->get('student_id');

        $activities = $activityModel->where('status', 'approved')
            ->orderBy('start_date', 'ASC')
            ->findAll();

        // ดึงข้อมูลการสมัครของนักศึกษาคนนี้
        $userRegs = $regModel->where('student_id', $studentId)->findAll();
        $regStatusMap = [];
        foreach ($userRegs as $reg) {
            $regStatusMap[$reg['activity_id']] = $reg['status'];
        }

        $data['activities'] = $activities;
        $data['reg_status_map'] = $regStatusMap;

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
        $studentId = session()->get('student_id');
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
            'student_id' => $studentId,
            'register_date' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        return redirect()->to('dashboard')->with('success', 'สมัครเข้าร่วมกิจกรรมสำเร็จแล้ว!');
    }

    // -------------------------------------------------------------------------
    //  ✅ ฟังก์ชันประวัติกิจกรรม
    // -------------------------------------------------------------------------
    public function history()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('login');
        }

        $studentId = session()->get('student_id');
        $db = \Config\Database::connect();

        // Join ตารางเพื่อให้ได้ชื่อกิจกรรม และ feedback
        $data['history'] = $db->table('activity_registrations')
            ->select('activity_registrations.*, activities.activity_name, activities.location, activities.start_date, activity_feedback.rating, activity_feedback.comment')
            ->join('activities', 'activities.activity_id = activity_registrations.activity_id')
            ->join('activity_feedback', 'activity_feedback.activity_id = activity_registrations.activity_id AND activity_feedback.student_id = activity_registrations.student_id', 'left')
            ->where('activity_registrations.student_id', $studentId)
            ->orderBy('activity_registrations.register_date', 'DESC')
            ->get()
            ->getResultArray();

        return view('student/history', $data);
    }

    // -------------------------------------------------------------------------
    //  หน้าแก้ไขข้อมูลส่วนตัว
    // -------------------------------------------------------------------------
    public function profile()
    {
        if (!session()->get('is_logged_in'))
            return redirect()->to('login');

        $model = new \App\Models\StudentModel();
        // ดึงข้อมูลนักศึกษาปัจจุบัน
        $data['student'] = $model->find(session()->get('student_id'));

        return view('student/profile', $data);
    }

    public function update_profile()
    {
        if (!session()->get('is_logged_in'))
            return redirect()->to('login');

        $model = new \App\Models\StudentModel();
        $id = session()->get('student_id');
        $student = $model->find($id);

        $data = [
            'phone_number' => $this->request->getPost('phone_number'),
            'email' => $this->request->getPost('email'),
        ];

        // --- เพิ่มการจัดการไฟล์ Avatar ---
        $img = $this->request->getFile('avatar');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // สุ่มชื่อใหม่
            $newName = $img->getRandomName();
            // ย้ายไฟล์ไปที่ public/uploads/avatars/
            $img->move(FCPATH . 'uploads/avatars', $newName);

            // ลบไฟล์เก่า (ถ้ามี)
            if (!empty($student['avatar'])) {
                $oldPath = FCPATH . 'uploads/avatars/' . $student['avatar'];
                if (file_exists($oldPath))
                    @unlink($oldPath);
            }

            $data['avatar'] = $newName;
        }

        // ถ้ามีการกรอกรหัสผ่านใหม่ ให้เปลี่ยนด้วย
        $newPass = $this->request->getPost('password');
        if (!empty($newPass)) {
            $data['password'] = password_hash($newPass, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);

        // อัปเดต Session ถ้ามีการเปลี่ยนข้อมูลบางอย่าง (เช่น รูปอาจจะใช้โชว์)
        if (isset($data['avatar'])) {
            session()->set('avatar', $data['avatar']);
        }

        return redirect()->to('dashboard')->with('success', 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }

    // -------------------------------------------------------------------------
    //  ฟังก์ชัน ให้ข้อมูลป้อนกลับกิจกรรม (Activity Feedback)
    // -------------------------------------------------------------------------
    public function feedback($activityId)
    {
        if (!session()->get('is_logged_in'))
            return redirect()->to('login');

        $activityModel = new \App\Models\ActivityModel();
        $regModel = new \App\Models\RegistrationModel();
        $studentId = session()->get('student_id');

        $registration = $regModel->where('activity_id', $activityId)
            ->where('student_id', $studentId)
            ->first();

        if (!$registration || $registration['status'] != 'approved' || empty($registration['checkin_time'])) {
            return redirect()->back()->with('error', 'คุณยังไม่มีสิทธิ์ประเมินกิจกรรมนี้ (ต้องเช็คชื่อเข้าร่วมก่อน)');
        }

        $data['activity'] = $activityModel->find($activityId);
        $fbModel = new \App\Models\FeedbackModel();
        $data['existing_feedback'] = $fbModel->where('activity_id', $activityId)
            ->where('student_id', $studentId)
            ->first();

        return view('student/feedback_form', $data);
    }

    public function save_feedback()
    {
        if (!session()->get('is_logged_in'))
            return redirect()->to('login');

        $fbModel = new \App\Models\FeedbackModel();
        $studentId = session()->get('student_id');
        $activityId = $this->request->getPost('activity_id');

        $data = [
            'activity_id' => $activityId,
            'student_id' => $studentId,
            'rating' => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment'),
        ];

        $existing = $fbModel->where('activity_id', $activityId)
            ->where('student_id', $studentId)
            ->first();

        if ($existing) {
            $fbModel->update($existing['feedback_id'], $data);
        } else {
            $fbModel->insert($data);
        }

        return redirect()->to('student/history')->with('success', 'ขอบคุณสำหรับข้อมูลป้อนกลับครับ!');
    }
}