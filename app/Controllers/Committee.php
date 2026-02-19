<?php namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\RegistrationModel;
use App\Models\StudentModel;

class Committee extends BaseController
{
    private function checkAuth() {
        if (!session()->get('is_logged_in')) return false;
        if (session()->get('role') !== 'committee') return false; // เช็ค Role ต้องเป็น committee
        return true;
    }

    // --- 1. หน้า Dashboard หลัก ---
    public function index()
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
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
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $db = \Config\Database::connect();

        // ✅ แก้ไข: ลบ committee_members.phone ออก เพราะเบอร์โทรอยู่ใน students.* แล้ว
        $data['students'] = $db->table('committee_members')
                               ->select('students.*, club_positions.position_name') 
                               ->join('students', 'students.student_id = committee_members.student_id')
                               ->join('club_positions', 'club_positions.position_id = committee_members.position_id')
                               ->orderBy('club_positions.position_id', 'ASC')
                               ->get()
                               ->getResultArray();
        
        return view('committee/members', $data);
    }

    // --- 3. จัดการกิจกรรม (1.3.2.2) ---
    public function activities()
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $activityModel = new ActivityModel();
        $data['activities'] = $activityModel->orderBy('start_date', 'DESC')->findAll();
        
        return view('committee/activities', $data);
    }

    public function create_activity() {
    if (!$this->checkAuth()) return redirect()->to('login');
    
    $db = \Config\Database::connect();
    // ดึงรายชื่ออาจารย์ทั้งหมดมาให้เลือกใน Select Box
    $data['advisors'] = $db->table('advisors')->get()->getResultArray();
    
    return view('committee/form_activity', $data);
}

    public function save_activity() {
    if (!$this->checkAuth()) return redirect()->to('login');
    
    $db = \Config\Database::connect();
    $currentYear = $db->table('academic_years')->where('is_current', 1)->get()->getRowArray();

    if (!$currentYear) {
        return redirect()->back()->with('error', 'กรุณาตั้งค่าปีการศึกษาปัจจุบันก่อน');
    }

    $model = new \App\Models\ActivityModel();
    $model->save([
        'activity_name'    => $this->request->getPost('activity_name'),
        'description'      => $this->request->getPost('description'),
        'start_date'       => $this->request->getPost('start_date'),
        'end_date'         => $this->request->getPost('end_date'),
        'location'         => $this->request->getPost('location'),
        'status'           => 'planning',
        'academic_year_id' => $currentYear['year_id'],
        'created_by_committee' => session()->get('student_id'),
        'advisors_id'      => $this->request->getPost('advisors_id') // ✅ รับค่าจากฟอร์ม
    ]);
    
    return redirect()->to('committee/activities')->with('success', 'บันทึกเรียบร้อย');
}
    
    // (ฟังก์ชัน Edit/Update/Delete ใส่เพิ่มตาม Pattern เดียวกันได้เลยครับ)

    // --- 4. ตรวจสอบการเข้าร่วม (1.3.2.3) ---
    public function participation_list()
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $activityModel = new ActivityModel();
        $data['activities'] = $activityModel->orderBy('start_date', 'DESC')->findAll();
        
        return view('committee/participation_list', $data);
    }
    
    // ใช้ฟังก์ชัน participants() และ update_status() เดิมที่มีอยู่แล้วสำหรับหน้าเช็คชื่อ
    public function participants($activityId)
    {
        if (!$this->checkAuth()) return redirect()->to('login');

        $db = \Config\Database::connect();
        $activityModel = new ActivityModel();

        // 1. ดึงข้อมูลกิจกรรม
        $data['activity'] = $activityModel->find($activityId);

        if (!$data['activity']) {
             return redirect()->to('committee/check_participation')->with('error', 'ไม่พบกิจกรรม');
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
        if (!$this->checkAuth()) return redirect()->to('login');

        $regModel = new RegistrationModel();
        
        // อัปเดตสถานะ (approved / rejected)
        $regModel->update($regId, ['status' => $status]);

        return redirect()->back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }


    // --- 5. รายงาน (1.3.2.4 & 1.3.2.5) ---
   public function reports()
    {
        $db = \Config\Database::connect();
        $activityModel = new \App\Models\ActivityModel();

        // 1. ข้อมูลสรุปตัวเลข (เหมือนเดิม)
        $data['total_activities'] = $activityModel->countAllResults();
        $query = $db->query("SELECT COUNT(*) as total FROM activity_registrations");
        $data['total_participants'] = $query->getRow()->total;

        // 2. กราฟแท่ง & วงกลม (เหมือนเดิม)
        $sqlBar = "SELECT a.activity_name, COUNT(r.student_id) as student_count 
                   FROM activities a 
                   LEFT JOIN activity_registrations r ON a.activity_id = r.activity_id 
                   GROUP BY a.activity_id ORDER BY student_count DESC";
        $data['bar_chart_data'] = $db->query($sqlBar)->getResultArray();

        $sqlPie = "SELECT m.major_name, COUNT(r.student_id) as count 
                   FROM activity_registrations r
                   JOIN students s ON r.student_id = s.student_id
                   JOIN majors m ON s.major_id = m.major_id
                   GROUP BY m.major_id";
        $data['pie_chart_data'] = $db->query($sqlPie)->getResultArray();

        // 3. (✅ เพิ่มใหม่) 5 อันดับนักกิจกรรมตัวยง (Top 5 Students)
        $sqlTopStudents = "SELECT s.student_id, s.full_name, m.major_name, COUNT(r.activity_id) as join_count
                           FROM activity_registrations r
                           JOIN students s ON r.student_id = s.student_id
                           LEFT JOIN majors m ON s.major_id = m.major_id
                           GROUP BY s.student_id
                           ORDER BY join_count DESC
                           LIMIT 5";
        $data['top_students'] = $db->query($sqlTopStudents)->getResultArray();

        // 4. รายการกิจกรรม (เหมือนเดิม)
        $data['activities_list'] = $activityModel->orderBy('start_date', 'DESC')->findAll();

        return view('committee/report_dashboard', $data);
    }
    // -------------------------------------------------------------------------
    //  ฟังก์ชัน แก้ไข และ ลบ กิจกรรม
    // -------------------------------------------------------------------------
    
    public function edit_activity($id)
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $model = new ActivityModel();
        $data['activity'] = $model->find($id);

        if (!$data['activity']) {
            return redirect()->to('committee/activities')->with('error', 'ไม่พบกิจกรรมที่ต้องการแก้ไข');
        }

        return view('committee/edit_activity', $data);
    }

    public function update_activity($id)
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $model = new ActivityModel();
        
        $data = [
            'activity_name' => $this->request->getPost('activity_name'),
            'description'   => $this->request->getPost('description'),
            'start_date'    => $this->request->getPost('start_date'),
            'end_date'      => $this->request->getPost('end_date'),
            'location'      => $this->request->getPost('location'),
            'status'        => $this->request->getPost('status') // อัปเดตสถานะได้ (planning/approved/closed)
        ];

        $model->update($id, $data);

        return redirect()->to('committee/activities')->with('success', 'แก้ไขข้อมูลกิจกรรมเรียบร้อยแล้ว');
    }

    public function delete_activity($id)
    {
        if (!$this->checkAuth()) return redirect()->to('login');
        
        $activityModel = new ActivityModel();
        $db = \Config\Database::connect();
        
        // ลบข้อมูลการสมัครของกิจกรรมนี้ก่อน (เพื่อไม่ให้ค้างใน DB)
        $db->table('activity_registrations')->where('activity_id', $id)->delete();
        
        // ลบตัวกิจกรรม
        $activityModel->delete($id);

        return redirect()->to('committee/activities')->with('success', 'ลบกิจกรรมเรียบร้อยแล้ว');
    }

    public function cancel_activity($id)
{
    if (!$this->checkAuth()) return redirect()->to('login');
    
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