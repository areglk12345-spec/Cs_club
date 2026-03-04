<?php
namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\RegistrationModel;
use App\Models\StudentModel;

class Committee extends BaseController
{
    private function checkAuth()
    {
        if (!session()->get('is_logged_in'))
            return false;
        if (session()->get('role') !== 'committee')
            return false; // เช็ค Role ต้องเป็น committee
        return true;
    }

    // --- 1. หน้า Dashboard หลัก ---
    public function index()
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $db = \Config\Database::connect();

        // ข้อมูลสรุปเพื่อโชว์ใน Dashboard
        $data['count_members'] = $db->table('students')->countAll();
        $data['count_activities'] = $db->table('activities')->countAll();
        $data['count_pending'] = $db->table('activity_registrations')->where('status', 'pending')->countAllResults();

        return view('committee/dashboard', $data);
    }

    // --- 2. จัดการข้อมูลสมาชิก (1.3.2.1) ---
    // --- 2. จัดการข้อมูลสมาชิกสโมสร (เฉพาะกรรมการ) ---
    public function members()
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $db = \Config\Database::connect();

        // รับค่าจาก Filter
        $search = $this->request->getGet('search');
        $majorId = $this->request->getGet('major_id');
        $posId = $this->request->getGet('position_id');

        $builder = $db->table('committee_members')
            ->select('students.*, club_positions.position_name, majors.major_name')
            ->join('students', 'students.student_id = committee_members.student_id')
            ->join('club_positions', 'club_positions.position_id = committee_members.position_id')
            ->join('majors', 'majors.major_id = students.major_id', 'left');

        // กรองข้อมูล
        if (!empty($search)) {
            $builder->groupStart()
                ->like('students.full_name', $search)
                ->orLike('students.student_id', $search)
                ->groupEnd();
        }
        if (!empty($majorId)) {
            $builder->where('students.major_id', $majorId);
        }
        if (!empty($posId)) {
            $builder->where('committee_members.position_id', $posId);
        }

        $data['students'] = $builder->orderBy('club_positions.position_id', 'ASC')
            ->get()
            ->getResultArray();

        // ดึงข้อมูลสำหรับ Dropdown
        $data['majors'] = $db->table('majors')->get()->getResultArray();
        $data['positions'] = $db->table('club_positions')->get()->getResultArray();

        // ส่งค่าเดิมกลับไปโชว์ใน Filter
        $data['filters'] = [
            'search' => $search,
            'major_id' => $majorId,
            'position_id' => $posId
        ];

        return view('committee/members', $data);
    }

    // --- 3. จัดการกิจกรรม (1.3.2.2) ---
    public function activities()
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $activityModel = new ActivityModel();
        $data['activities'] = $activityModel->orderBy('start_date', 'DESC')->findAll();

        return view('committee/activities', $data);
    }

    public function create_activity()
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $db = \Config\Database::connect();
        // ดึงรายชื่ออาจารย์ทั้งหมดมาให้เลือกใน Select Box
        $data['advisors'] = $db->table('advisors')->get()->getResultArray();

        return view('committee/form_activity', $data);
    }

    public function save_activity()
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $db = \Config\Database::connect();
        $currentYear = $db->table('academic_years')->where('is_current', 1)->get()->getRowArray();

        if (!$currentYear) {
            return redirect()->back()->with('error', 'กรุณาตั้งค่าปีการศึกษาปัจจุบันก่อน');
        }

        $model = new ActivityModel();
        $data = [
            'activity_name' => $this->request->getPost('activity_name'),
            'description' => $this->request->getPost('description'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'location' => $this->request->getPost('location'),
            'status' => 'planning',
            'academic_year_id' => $currentYear['year_id'],
            'created_by_committee' => session()->get('student_id'),
            'advisors_id' => $this->request->getPost('advisors_id')
        ];

        // จัดการอัปโหลดรูปปก
        $img = $this->request->getFile('cover_image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(FCPATH . 'uploads/activities', $newName);
            $data['cover_image'] = $newName;
        }

        $model->save($data);

        return redirect()->to('committee/activities')->with('success', 'บันทึกเรียบร้อย');
    }

    // (ฟังก์ชัน Edit/Update/Delete ใส่เพิ่มตาม Pattern เดียวกันได้เลยครับ)

    // --- 4. ตรวจสอบการเข้าร่วม (1.3.2.3) ---
    public function participation_list()
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $activityModel = new ActivityModel();
        $data['activities'] = $activityModel->orderBy('start_date', 'DESC')->findAll();

        return view('committee/participation_list', $data);
    }

    // ใช้ฟังก์ชัน participants() และ update_status() เดิมที่มีอยู่แล้วสำหรับหน้าเช็คชื่อ
    public function participants($activityId)
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $db = \Config\Database::connect();
        $activityModel = new ActivityModel();

        // 1. ดึงข้อมูลกิจกรรม
        $data['activity'] = $activityModel->find($activityId);

        if (!$data['activity']) {
            return redirect()->to('committee/check_participation')->with('error', 'ไม่พบกิจกรรม');
        }

        // 1.5 สร้าง QR Token ถ้ายังไม่มี
        if (empty($data['activity']['qr_token'])) {
            $token = bin2hex(random_bytes(16));
            $activityModel->update($activityId, ['qr_token' => $token]);
            $data['activity']['qr_token'] = $token;
        }

        // 2. ดึงรายชื่อคนสมัคร + จอยเอาชื่อ-นามสกุล
        $data['participants'] = $db->table('activity_registrations')
            ->select('activity_registrations.*, students.full_name, students.student_id as std_id')
            ->join('students', 'students.student_id = activity_registrations.student_id')
            ->where('activity_registrations.activity_id', $activityId)
            ->orderBy('activity_registrations.register_date', 'ASC')
            ->get()
            ->getResultArray();

        return view('committee/participants', $data);
    }

    public function update_status($regId, $status)
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $regModel = new RegistrationModel();
        $db = \Config\Database::connect();

        // ดึงข้อมูลนักศึกษาและกิจกรรมก่อนอัปเดตเพื่อส่งเมล
        $reg = $db->table('activity_registrations')
            ->select('activity_registrations.*, students.full_name, students.email, activities.activity_name')
            ->join('students', 'students.student_id = activity_registrations.student_id')
            ->join('activities', 'activities.activity_id = activity_registrations.activity_id')
            ->where('registration_id', $regId)
            ->get()->getRowArray();

        // อัปเดตสถานะ (approved / rejected)
        $regModel->update($regId, ['status' => $status]);

        // ส่งอีเมลแจ้งเตือน (ถ้าเมลไม่ว่าง)
        if ($reg && !empty($reg['email'])) {
            helper('email');
            $subject = "ผลการสมัครเข้าร่วมกิจกรรม: " . $reg['activity_name'];
            $statusTh = ($status == 'approved') ? '<strong style="color: green;">ผ่านการอนุมัติ</strong>' : '<strong style="color: red;">ไม่ผ่านการอนุมัติ</strong>';

            $message = "
                <h3>สวัสดีคุณ {$reg['full_name']}</h3>
                <p>การสมัครเข้าร่วมกิจกรรม <strong>{$reg['activity_name']}</strong> ของคุณได้รับการตรวจสอบแล้ว</p>
                <p>ผลการตรวจสอบคือ: $statusTh</p>
                <p>ขอบคุณครับ<br>สโมสรนักศึกษา</p>
            ";

            send_activity_email($reg['email'], $subject, $message);
        }

        return redirect()->back()->with('success', 'อัปเดตสถานะและส่งอีเมลแจ้งเตือนเรียบร้อยแล้ว');
    }


    // --- 5. รายงาน (1.3.2.4 & 1.3.2.5) ---
    public function reports()
    {
        $db = \Config\Database::connect();
        $activityModel = new \App\Models\ActivityModel();

        // รับค่า Filter
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // 1. ข้อมูลสรุปตัวเลข
        $builderTotal = $activityModel;
        if (!empty($startDate))
            $builderTotal->where('start_date >=', $startDate);
        if (!empty($endDate))
            $builderTotal->where('start_date <=', $endDate . ' 23:59:59');
        $data['total_activities'] = $builderTotal->countAllResults();

        $sqlSumReg = "SELECT COUNT(*) as total FROM activity_registrations r
                      JOIN activities a ON r.activity_id = a.activity_id 
                      WHERE 1=1";
        if (!empty($startDate))
            $sqlSumReg .= " AND a.start_date >= '$startDate'";
        if (!empty($endDate))
            $sqlSumReg .= " AND a.start_date <= '$endDate 23:59:59'";
        $data['total_participants'] = $db->query($sqlSumReg)->getRow()->total;

        // 2. กราฟแท่ง (กรองตามวันที่)
        $sqlBar = "SELECT a.activity_name, COUNT(r.student_id) as student_count 
                   FROM activities a 
                   LEFT JOIN activity_registrations r ON a.activity_id = r.activity_id 
                   WHERE 1=1";
        if (!empty($startDate))
            $sqlBar .= " AND a.start_date >= '$startDate'";
        if (!empty($endDate))
            $sqlBar .= " AND a.start_date <= '$endDate 23:59:59'";
        $sqlBar .= " GROUP BY a.activity_id ORDER BY student_count DESC LIMIT 10";
        $data['bar_chart_data'] = $db->query($sqlBar)->getResultArray();

        // 3. กราฟวงกลม (กรองตามวันที่)
        $sqlPie = "SELECT m.major_name, COUNT(r.student_id) as count 
                   FROM activity_registrations r
                   JOIN students s ON r.student_id = s.student_id
                   JOIN majors m ON s.major_id = m.major_id
                   JOIN activities a ON r.activity_id = a.activity_id
                   WHERE 1=1";
        if (!empty($startDate))
            $sqlPie .= " AND a.start_date >= '$startDate'";
        if (!empty($endDate))
            $sqlPie .= " AND a.start_date <= '$endDate 23:59:59'";
        $sqlPie .= " GROUP BY m.major_id";
        $data['pie_chart_data'] = $db->query($sqlPie)->getResultArray();

        // ---------------------------------------------------------
        // (Logic อื่นๆ เหมือนเดิม แต่ถ้าจะกรอง Top Students ด้วยก็ต้องเพิ่ม JOIN Activities)
        // ---------------------------------------------------------

        // 5. รายการกิจกรรม (กรองตามวันที่)
        $builderList = $activityModel->orderBy('start_date', 'DESC');
        if (!empty($startDate))
            $builderList->where('start_date >=', $startDate);
        if (!empty($endDate))
            $builderList->where('start_date <=', $endDate . ' 23:59:59');
        $data['activities_list'] = $builderList->findAll();

        $data['filters'] = ['start_date' => $startDate, 'end_date' => $endDate];

        // 3. (✅ เพิ่มใหม่) 5 อันดับนักกิจกรรมตัวยง (Top 5 Students)
        $sqlTopStudents = "SELECT s.student_id, s.full_name, m.major_name, COUNT(r.activity_id) as join_count
                           FROM activity_registrations r
                           JOIN students s ON r.student_id = s.student_id
                           LEFT JOIN majors m ON s.major_id = m.major_id
                           GROUP BY s.student_id
                           ORDER BY join_count DESC
                           LIMIT 5";
        $data['top_students'] = $db->query($sqlTopStudents)->getResultArray();

        // 4. (✅ เพิ่มใหม่) สรุป Feedback กิจกรรม
        $sqlFeedback = "SELECT f.activity_id, AVG(f.rating) as avg_rating, COUNT(f.feedback_id) as feedback_count
                         FROM activity_feedback f
                         GROUP BY f.activity_id";
        $feedbacksData = $db->query($sqlFeedback)->getResultArray();

        // แปลงเป็น Array ที่เข้าถึงได้ง่าย [activity_id => info]
        $data['feedback_summary'] = [];
        foreach ($feedbacksData as $f) {
            $data['feedback_summary'][$f['activity_id']] = $f;
        }

        return view('committee/report_dashboard', $data);
    }
    // -------------------------------------------------------------------------
    //  ฟังก์ชัน แก้ไข และ ลบ กิจกรรม
    // -------------------------------------------------------------------------

    public function edit_activity($id)
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $model = new ActivityModel();
        $data['activity'] = $model->find($id);

        if (!$data['activity']) {
            return redirect()->to('committee/activities')->with('error', 'ไม่พบกิจกรรมที่ต้องการแก้ไข');
        }

        return view('committee/edit_activity', $data);
    }

    public function update_activity($id)
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $model = new ActivityModel();
        $activity = $model->find($id);

        $data = [
            'activity_name' => $this->request->getPost('activity_name'),
            'description' => $this->request->getPost('description'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'location' => $this->request->getPost('location'),
            'status' => $this->request->getPost('status')
        ];

        // จัดการอัปโหลดรูปปกใหม่
        $img = $this->request->getFile('cover_image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(FCPATH . 'uploads/activities', $newName);

            // ลบรูปเก่า
            if (!empty($activity['cover_image'])) {
                $oldPath = FCPATH . 'uploads/activities/' . $activity['cover_image'];
                if (file_exists($oldPath))
                    @unlink($oldPath);
            }
            $data['cover_image'] = $newName;
        }

        $model->update($id, $data);

        return redirect()->to('committee/activities')->with('success', 'แก้ไขข้อมูลกิจกรรมเรียบร้อยแล้ว');
    }

    public function delete_activity($id)
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $activityModel = new ActivityModel();
        $activity = $activityModel->find($id);
        $db = \Config\Database::connect();

        // ลบข้อมูลการสมัครของกิจกรรมนี้ก่อน
        $db->table('activity_registrations')->where('activity_id', $id)->delete();

        // ลบรูปภาพปก
        if ($activity && !empty($activity['cover_image'])) {
            $path = FCPATH . 'uploads/activities/' . $activity['cover_image'];
            if (file_exists($path))
                @unlink($path);
        }

        // ลบตัวกิจกรรม
        $activityModel->delete($id);

        return redirect()->to('committee/activities')->with('success', 'ลบกิจกรรมเรียบร้อยแล้ว');
    }

    public function cancel_activity($id)
    {
        if (!$this->checkAuth())
            return redirect()->to('login');

        $model = new \App\Models\ActivityModel();

        // ตรวจสอบก่อนว่าเป็นกิจกรรมของปีปัจจุบันหรือไม่ (เพื่อความปลอดภัย)
        $activity = $model->find($id);
        if ($activity['status'] == 'completed') {
            return redirect()->back()->with('error', 'กิจกรรมที่เสร็จสิ้นแล้วไม่สามารถยกเลิกได้');
        }

        $model->update($id, ['status' => 'cancelled']);

        return redirect()->to('committee/activities')->with('success', 'ยกเลิกกิจกรรมเรียบร้อยแล้ว');
    }

}