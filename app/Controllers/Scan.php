<?php
namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\RegistrationModel;

class Scan extends BaseController
{
    public function index($activityId, $token)
    {
        // 1. ตรวจสอบ Login
        if (!session()->get('is_logged_in')) {
            session()->setFlashdata('error', 'กรุณาเข้าสู่ระบบก่อนสแกน');
            return redirect()->to('login');
        }

        $studentId = session()->get('student_id');
        $activityModel = new ActivityModel();
        $regModel = new RegistrationModel();

        // 2. ตรวจสอบกิจกรรมและ Token
        $activity = $activityModel->find($activityId);
        if (!$activity || $activity['qr_token'] !== $token) {
            return view('scan_result', [
                'status' => 'error',
                'message' => 'QR Code ไม่ถูกต้อง หรือกิจกรรมนี้ไม่มีอยู่จริง'
            ]);
        }

        // 3. ตรวจสอบการลงทะเบียน
        $registration = $regModel->where([
            'activity_id' => $activityId,
            'student_id' => $studentId
        ])->first();

        if (!$registration) {
            return view('scan_result', [
                'status' => 'error',
                'message' => 'คุณยังไม่ได้ลงทะเบียนเข้าร่วมกิจกรรมนี้ กรุณาลงทะเบียนก่อน'
            ]);
        }

        // 4. ตรวจสอบว่าเคยเช็คอินไปหรือยัง
        if ($registration['status'] == 'approved' && !empty($registration['checkin_time'])) {
            return view('scan_result', [
                'status' => 'warning',
                'message' => 'คุณได้เช็คอินเข้าร่วมกิจกรรมนี้ไปแล้วเมื่อ ' . date('d/m/Y H:i', strtotime($registration['checkin_time'])),
                'activity' => $activity
            ]);
        }

        // 5. บันทึกการเช็คอิน
        $regModel->update($registration['registration_id'], [
            'status' => 'approved',
            'checkin_time' => date('Y-m-d H:i:s')
        ]);

        return view('scan_result', [
            'status' => 'success',
            'message' => 'เช็คอินสำเร็จ! ยินดีต้อนรับเข้าสู่กิจกรรม',
            'activity' => $activity
        ]);
    }
}
