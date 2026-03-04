<?php
namespace App\Controllers;

class Admin extends BaseController
{
    public function dashboard()
    {
        // เช็ค Security
        if (!session()->get('is_logged_in') || session()->get('role') != 'admin') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();

        // 1. ข้อมูลสรุปบน Card
        $data['total_students'] = $db->table('students')->countAll();
        $data['total_activities'] = $db->table('activities')->countAll();
        $data['total_committee'] = $db->table('committee_members')->countAll();
        $data['total_majors'] = $db->table('majors')->countAll();

        // 2. ข้อมูลสำหรับกราฟ (จำนวนการสมัครแยกตามกิจกรรม)
        $chartData = $db->table('activities')
            ->select('activities.activity_name, COUNT(activity_registrations.registration_id) as total')
            ->join('activity_registrations', 'activity_registrations.activity_id = activities.activity_id', 'left')
            ->groupBy('activities.activity_id')
            ->orderBy('total', 'DESC')
            ->limit(8)
            ->get()->getResultArray();

        $data['chart_labels'] = array_column($chartData, 'activity_name');
        $data['chart_values'] = array_column($chartData, 'total');

        return view('admin/dashboard', $data);
    }

    // --- 1.3.1.1 จัดการข้อมูลสาขา ---

    // แสดงรายการสาขาทั้งหมด
    public function majors()
    {
        $majorModel = new \App\Models\MajorModel();
        $data['majors'] = $majorModel->findAll();

        return view('admin/manage_majors', $data);
    }

    // บันทึกสาขาใหม่
    public function save_major()
    {
        // ตรวจสอบสิทธิ์ Admin (ถ้ามี)
        $session = session();
        if ($session->get('role') != 'admin') {
            return redirect()->to('/login');
        }

        $majorName = $this->request->getPost('major_name');

        // 1. กำหนดกฎการตรวจสอบ (Validation Rules)
        // is_unique[majors.major_name] แปลว่า ห้ามซ้ำกับข้อมูลในตาราง majors คอลัมน์ major_name
        $rules = [
            'major_name' => [
                'rules' => 'required|is_unique[majors.major_name]',
                'errors' => [
                    'required' => 'กรุณากรอกชื่อสาขาวิชา',
                    'is_unique' => 'ชื่อสาขาวิชานี้มีอยู่แล้วในระบบ (ซ้ำ)' // ข้อความแจ้งเตือนเมื่อซ้ำ
                ]
            ]
        ];

        // 2. ทำการตรวจสอบ
        if (!$this->validate($rules)) {
            // ถ้าไม่ผ่าน (ซ้ำ) ให้เด้งกลับไปหน้าเดิมพร้อมข้อความแจ้งเตือน
            return redirect()->back()->with('error', $this->validator->getError('major_name'));
        }

        // 3. ถ้าไม่ซ้ำ ให้บันทึกตามปกติ
        $model = new \App\Models\MajorModel();
        $model->save([
            'major_name' => $majorName
        ]);

        // ❌ ของเดิม (ผิด): redirect()->to('admin/manage_majors')
        // ✅ ของใหม่ (แก้เป็น):
        return redirect()->to('admin/majors')->with('success', 'บันทึกข้อมูลสาขาวิชาเรียบร้อยแล้ว');
    }

    // ลบสาขา
    public function delete_major($id)
    {
        $majorModel = new \App\Models\MajorModel();
        $majorModel->delete($id);
        return redirect()->to('admin/majors')->with('success', 'ลบข้อมูลสำเร็จ');
    }
    public function edit_major($id)
    {
        $majorModel = new \App\Models\MajorModel();
        $data['major'] = $majorModel->find($id);

        if (!$data['major']) {
            return redirect()->to('admin/majors')->with('error', 'ไม่พบข้อมูลสาขา');
        }

        return view('admin/edit_major', $data);
    }

    // บันทึกการแก้ไขสาขา
    public function update_major($id)
    {
        $majorModel = new \App\Models\MajorModel();
        $majorName = $this->request->getPost('major_name');

        // กฎ: ห้ามซ้ำกับชื่ออื่น (ยกเว้นตัวเอง)
        // is_unique[majors.major_name,major_id,{$id}] แปลว่า เช็คซ้ำในตาราง majors แต่ข้าม id นี้ไป
        $rules = [
            'major_name' => [
                'rules' => "required|is_unique[majors.major_name,major_id,{$id}]",
                'errors' => [
                    'required' => 'กรุณากรอกชื่อสาขาวิชา',
                    'is_unique' => 'ชื่อสาขาวิชานี้มีอยู่แล้ว (ซ้ำ)'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getError('major_name'));
        }

        $majorModel->update($id, ['major_name' => $majorName]);

        return redirect()->to('admin/majors')->with('success', 'แก้ไขข้อมูลสาขาเรียบร้อย');
    }

    // --- 1.3.1.2 จัดการข้อมูลคณะกรรมการสโมสร ---

    public function committee()
    {
        $committeeModel = new \App\Models\CommitteeModel();
        $positionModel = new \App\Models\ClubPositionModel();
        $yearModel = new \App\Models\AcademicYearModel();

        // ดึงข้อมูลมาแสดงผล
        $data['committee_list'] = $committeeModel->getCommitteeDetails(); // รายชื่อกรรมการปัจจุบัน
        $data['positions'] = $positionModel->findAll();              // ตัวเลือกตำแหน่ง
        $data['years'] = $yearModel->orderBy('year_name', 'DESC')->findAll(); // ตัวเลือกปี

        return view('admin/manage_committee', $data);
    }

    public function save_committee()
    {
        $committeeModel = new \App\Models\CommitteeModel();
        $studentModel = new \App\Models\StudentModel();

        // รับค่าจากฟอร์ม
        $student_id = $this->request->getPost('student_id');
        $position_id = $this->request->getPost('position_id');
        $year_id = $this->request->getPost('academic_year_id');

        // 1. เช็คว่ารหัสนักศึกษานี้มีจริงไหม?
        if (!$studentModel->find($student_id)) {
            return redirect()->back()->with('error', 'ไม่พบรหัสนักศึกษานี้ในระบบ');
        }

        // 2. เช็คว่าซ้ำไหม (คนเดิม ปีเดิม ตำแหน่งเดิม)
        $exists = $committeeModel->where([
            'student_id' => $student_id,
            'academic_year_id' => $year_id
        ])->first();

        if ($exists) {
            return redirect()->back()->with('error', 'นักศึกษาคนนี้เป็นกรรมการในปีนี้แล้ว');
        }

        // 3. บันทึก
        $committeeModel->insert([
            'student_id' => $student_id,
            'position_id' => $position_id,
            'academic_year_id' => $year_id
        ]);

        return redirect()->to('admin/committee')->with('success', 'แต่งตั้งกรรมการสำเร็จ');
    }

    public function delete_committee($id)
    {
        $committeeModel = new \App\Models\CommitteeModel();
        $committeeModel->delete($id);
        return redirect()->to('admin/committee')->with('success', 'ลบข้อมูลสำเร็จ');
    }

    // ... (ต่อจากฟังก์ชัน delete_committee)

    // หน้าฟอร์มแก้ไขกรรมการ
    public function edit_committee($id)
    {
        $committeeModel = new \App\Models\CommitteeModel();
        $positionModel = new \App\Models\ClubPositionModel();
        $yearModel = new \App\Models\AcademicYearModel();
        $studentModel = new \App\Models\StudentModel();

        // ดึงข้อมูลกรรมการคนที่จะแก้ไข (Join ตารางเพื่อเอาชื่อนักศึกษา)
        $data['committee'] = $committeeModel->select('committee_members.*, students.full_name, students.student_id')
            ->join('students', 'students.student_id = committee_members.student_id')
            ->find($id);

        if (empty($data['committee'])) {
            return redirect()->to('admin/committee')->with('error', 'ไม่พบข้อมูลกรรมการที่ต้องการแก้ไข');
        }

        $data['positions'] = $positionModel->findAll();
        $data['years'] = $yearModel->orderBy('year_name', 'DESC')->findAll();

        return view('admin/edit_committee', $data);
    }

    // บันทึกการแก้ไข
    public function update_committee($id)
    {
        $committeeModel = new \App\Models\CommitteeModel();

        // รับค่าจากฟอร์ม (แก้ไขได้แค่ ตำแหน่ง และ ปีการศึกษา ส่วนรหัสนักศึกษาห้ามแก้ เพราะเป็นคนเดิม)
        $position_id = $this->request->getPost('position_id');
        $year_id = $this->request->getPost('academic_year_id');

        // ตรวจสอบความซ้ำซ้อน (คนเดิม ปีเดิม แต่ตำแหน่งเปลี่ยนได้)
        // แต่ต้องระวังไม่ให้ไปซ้ำกับคนอื่น หรือซ้ำกับตัวเองใน record อื่น (ถ้ามี Logic ซับซ้อนกว่านี้)
        // ในที่นี้เราจะอนุญาตให้แก้ได้เลย เพราะ ID ไม่เปลี่ยน

        $committeeModel->update($id, [
            'position_id' => $position_id,
            'academic_year_id' => $year_id
        ]);

        return redirect()->to('admin/committee')->with('success', 'แก้ไขข้อมูลกรรมการเรียบร้อยแล้ว');
    }

    // -------------------------------------------------------------------------
    //  ส่วนที่ 3: จัดการปีการศึกษา (1.3.1.3)
    // -------------------------------------------------------------------------
    public function years()
    {
        // เช็คสิทธิ์ admin
        if (!session()->get('is_logged_in') || session()->get('role') != 'admin') {
            return redirect()->to('/login');
        }

        $yearModel = new \App\Models\AcademicYearModel();

        $data['years'] = $yearModel
            ->orderBy('year_name', 'DESC')
            ->findAll();

        return view('admin/manage_years', $data);
    }

    public function save_year()
    {
        $yearModel = new \App\Models\AcademicYearModel();

        // 1. รับค่าจากฟอร์ม
        $yearName = $this->request->getPost('year_name');
        $isCurrent = $this->request->getPost('is_current');

        // 2. กำหนดกฎการตรวจสอบ (Validation Rules)
        // is_unique[academic_years.year_name] = ห้ามซ้ำกับข้อมูลในตาราง academic_years คอลัมน์ year_name
        $rules = [
            'year_name' => [
                'rules' => 'required|numeric|is_unique[academic_years.year_name]',
                'errors' => [
                    'required' => 'กรุณากรอกปีการศึกษา',
                    'numeric' => 'ปีการศึกษาต้องเป็นตัวเลขเท่านั้น',
                    'is_unique' => 'ปีการศึกษานี้มีอยู่แล้วในระบบ (ซ้ำ)' // ❌ ข้อความแจ้งเตือนที่คุณต้องการ
                ]
            ]
        ];

        // 3. เริ่มตรวจสอบ
        if (!$this->validate($rules)) {
            // ถ้าข้อมูลซ้ำ ให้เด้งกลับไปหน้าเดิมพร้อมข้อความ Error และค่า input เดิม
            return redirect()->back()->withInput()->with('error', $this->validator->getError('year_name'));
        }

        // 4. ถ้าผ่านการตรวจสอบ (ไม่ซ้ำ) ให้ดำเนินการบันทึก

        // ตรรกะเดิม: ถ้าเลือกเป็นปีปัจจุบัน ให้เคลียร์ค่าปีอื่นก่อน
        if ($isCurrent) {
            $yearModel->where('year_id >', 0)->set('is_current', 0)->update();
        }

        // บันทึกข้อมูล
        $yearModel->insert([
            'year_name' => $yearName,
            'is_current' => ($isCurrent) ? 1 : 0
        ]);

        return redirect()->to('admin/years')->with('success', 'บันทึกปีการศึกษาเรียบร้อยแล้ว');
    }

    public function delete_year($id)
    {
        $yearModel = new \App\Models\AcademicYearModel();
        $db = \Config\Database::connect(); // เรียกใช้ Database โดยตรง

        // -----------------------------------------------------------
        // 🧹 ขั้นตอนที่ 1: ล้างข้อมูลที่เกี่ยวข้องออกให้หมด (Force Clean)
        // -----------------------------------------------------------

        // 1.1 ลบ "กิจกรรม" ที่จัดในปีนี้ (ถ้ามีซ่อนอยู่)
        // (เช็คชื่อตารางใน DB ของคุณด้วย ถ้าชื่อ activities ก็ใช้ตามนี้)
        $db->table('activities')->where('academic_year_id', $id)->delete();

        // 1.2 ลบ "คณะกรรมการ" ที่สังกัดปีนี้ (ถ้ามีซ่อนอยู่)
        // (เช็คชื่อตารางใน DB ของคุณด้วย ถ้าชื่อ committee_members ก็ใช้ตามนี้)
        $db->table('committee_members')->where('academic_year_id', $id)->delete();

        // -----------------------------------------------------------
        // 🗑️ ขั้นตอนที่ 2: ลบปีการศึกษา
        // -----------------------------------------------------------
        $yearModel->delete($id);

        return redirect()->to('admin/years')->with('success', 'ลบปีการศึกษาและข้อมูลตกค้างทั้งหมดเรียบร้อยแล้ว');
    }

    // แสดงฟอร์มแก้ไขปีการศึกษา
    public function edit_year($id)
    {
        $yearModel = new \App\Models\AcademicYearModel();
        $data['year'] = $yearModel->find($id);

        if (!$data['year'])
            return redirect()->to('admin/years')->with('error', 'ไม่พบข้อมูล');

        return view('admin/edit_year', $data);
    }

    // บันทึกการแก้ไขปีการศึกษา
    public function update_year($id)
    {
        $yearModel = new \App\Models\AcademicYearModel();
        $yearName = $this->request->getPost('year_name');
        $isCurrent = $this->request->getPost('is_current');

        $rules = [
            'year_name' => [
                'rules' => "required|numeric|is_unique[academic_years.year_name,year_id,{$id}]",
                'errors' => [
                    'required' => 'กรุณากรอกปี',
                    'numeric' => 'ต้องเป็นตัวเลข',
                    'is_unique' => 'ปีนี้มีอยู่แล้ว'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getError('year_name'));
        }

        // ถ้าตั้งเป็นปีปัจจุบัน ให้ปลดปีอื่นออก
        if ($isCurrent) {
            $yearModel->where('year_id >', 0)->set('is_current', 0)->update();
        }

        $yearModel->update($id, [
            'year_name' => $yearName,
            'is_current' => ($isCurrent) ? 1 : 0
        ]);

        return redirect()->to('admin/years')->with('success', 'แก้ไขปีการศึกษาเรียบร้อย');
    }

    // ฟังก์ชันสำหรับกดปุ่ม "ตั้งเป็นปีปัจจุบัน" จากตารางโดยตรง
    public function set_current_year($id)
    {
        $yearModel = new \App\Models\AcademicYearModel();

        // 1. ปลดทุกปีออกก่อน
        // ✅ แก้ไขบรรทัดนี้: เติม where('year_id >', 0) เพื่อยืนยันการแก้ทุกแถว
        $yearModel->where('year_id >', 0)->set('is_current', 0)->update();

        // 2. ตั้งปีที่เลือกเป็น 1
        $yearModel->update($id, ['is_current' => 1]);

        return redirect()->to('admin/years')->with('success', 'อัปเดตปีการศึกษาปัจจุบันเรียบร้อย');
    }

    // -------------------------------------------------------------------------
    //  ส่วนที่ 4: จัดการตำแหน่งสโมสร (1.3.1.4)
    // -------------------------------------------------------------------------

    // 1. แสดงรายการตำแหน่งทั้งหมด
    public function positions()
    {
        // เรียกใช้ Model
        $positionModel = new \App\Models\ClubPositionModel();

        // ดึงข้อมูลทั้งหมดส่งไปที่หน้า View
        $data['positions'] = $positionModel->findAll();

        return view('admin/manage_positions', $data);
    }

    // 2. บันทึกตำแหน่งใหม่
    // 2. บันทึกตำแหน่งใหม่ (แก้ไข: เพิ่มการป้องกันข้อมูลซ้ำ)
    public function save_position()
    {
        // ตรวจสอบสิทธิ์ Admin
        if (session()->get('role') != 'admin')
            return redirect()->to('/login');

        $positionModel = new \App\Models\ClubPositionModel();
        $name = $this->request->getPost('position_name');

        // 1. กำหนดกฎการตรวจสอบ (Validation)
        // สมมติว่าตารางชื่อ 'club_positions' ถ้าชื่อตารางคุณต่างจากนี้ ให้แก้ตรง is_unique[...]
        $rules = [
            'position_name' => [
                'rules' => 'required|is_unique[club_positions.position_name]',
                'errors' => [
                    'required' => 'กรุณากรอกชื่อตำแหน่ง',
                    'is_unique' => 'ชื่อตำแหน่งนี้มีอยู่แล้วในระบบ (ซ้ำ)' // แจ้งเตือนเมื่อซ้ำ
                ]
            ]
        ];

        // 2. ทำการตรวจสอบ
        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', $this->validator->getError('position_name'));
        }

        // 3. บันทึกลงฐานข้อมูล
        $positionModel->insert(['position_name' => $name]);

        return redirect()->to('admin/positions')->with('success', 'เพิ่มตำแหน่งสำเร็จ');


        return redirect()->back()->with('error', 'กรุณากรอกชื่อตำแหน่ง');
    }

    // 3. ลบตำแหน่ง
    // แก้ไขฟังก์ชันนี้ใน Admin.php
    public function delete_position($id)
    {
        $positionModel = new \App\Models\ClubPositionModel();

        try {
            // ลองสั่งลบ
            $positionModel->delete($id);
            return redirect()->to('admin/positions')->with('success', 'ลบตำแหน่งเรียบร้อย');

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // ถ้าเจอ Error 1451 (ติด Foreign Key) ให้แจ้งเตือนแทน
            if ($e->getCode() == 1451) {
                return redirect()->back()->with('error', 'ไม่สามารถลบตำแหน่งนี้ได้ เนื่องจากยังมีกรรมการดำรงตำแหน่งนี้อยู่ (กรุณาลบหรือเปลี่ยนตำแหน่งกรรมการคนนั้นก่อน)');
            }

            // ถ้าเป็น Error อื่นๆ ให้แสดงตามปกติ
            throw $e;
        }
    }

    // แสดงฟอร์มแก้ไขตำแหน่ง
    public function edit_position($id)
    {
        $positionModel = new \App\Models\ClubPositionModel();
        $data['position'] = $positionModel->find($id);

        if (!$data['position'])
            return redirect()->to('admin/positions')->with('error', 'ไม่พบข้อมูล');

        return view('admin/edit_position', $data);
    }

    // บันทึกการแก้ไขตำแหน่ง
    public function update_position($id)
    {
        $positionModel = new \App\Models\ClubPositionModel();
        $name = $this->request->getPost('position_name');

        // กฎ: ห้ามซ้ำกับ ID อื่น
        // ตรวจสอบชื่อตารางใน DB ของคุณด้วย (club_positions หรือ positions)
        // รูปแบบ: is_unique[ตาราง.คอลัมน์,คอลัมน์_id,ค่า_id_ปัจจุบัน]
        $rules = [
            'position_name' => [
                'rules' => "required|is_unique[club_positions.position_name,position_id,{$id}]",
                'errors' => ['required' => 'กรุณากรอกชื่อ', 'is_unique' => 'ชื่อตำแหน่งซ้ำ']
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getError('position_name'));
        }

        $positionModel->update($id, ['position_name' => $name]);

        return redirect()->to('admin/positions')->with('success', 'แก้ไขตำแหน่งเรียบร้อย');
    }
    // =========================================================================
    //  ส่วนที่ 5: จัดการข้อมูลนักศึกษา (Student Management)
    // =========================================================================

    // =========================================================================
    //  ส่วนที่ 5: จัดการข้อมูลนักศึกษา (Student Management)
    // =========================================================================

    public function students()
    {
        $studentModel = new \App\Models\StudentModel();
        $committeeModel = new \App\Models\CommitteeModel();
        $majorModel = new \App\Models\MajorModel();

        // รับค่า Filter
        $search = $this->request->getGet('search');
        $majorId = $this->request->getGet('major_id');

        // 1. หาว่าใครเป็นกรรมการบ้าง?
        $committeeIds = $committeeModel->findColumn('student_id');

        // 2. เตรียม Query นักศึกษา
        $builder = $studentModel->select('students.*, majors.major_name')
            ->join('majors', 'majors.major_id = students.major_id', 'left');

        // 3. กรองข้อมูล
        if (!empty($search)) {
            $builder->groupStart()
                ->like('students.full_name', $search)
                ->orLike('students.student_id', $search)
                ->groupEnd();
        }
        if (!empty($majorId)) {
            $builder->where('students.major_id', $majorId);
        }

        // 4. ถ้าต้องการแยกกรรมการออกจากรายชื่อนักศึกษาทั่วไป (ตาม Logic เดิม)
        if (!empty($committeeIds)) {
            $builder->whereNotIn('students.student_id', $committeeIds);
        }

        $data['students'] = $builder->orderBy('students.student_id', 'ASC')->findAll();
        $data['majors'] = $majorModel->findAll();
        $data['filters'] = ['search' => $search, 'major_id' => $majorId];

        return view('admin/manage_students', $data);
    }

    // รีเซ็ตรหัสผ่านนักศึกษาเป็น '1234'
    public function reset_student_pass($id)
    {
        $studentModel = new \App\Models\StudentModel();
        $newPass = password_hash('1234', PASSWORD_DEFAULT);

        $studentModel->update($id, ['password' => $newPass]);

        return redirect()->to('admin/students')->with('success', 'รีเซ็ตรหัสผ่านเป็น 1234 เรียบร้อยแล้ว');
    }

    public function delete_student($id)
    {
        $studentModel = new \App\Models\StudentModel();
        $studentModel->delete($id);
        return redirect()->to('admin/students')->with('success', 'ลบข้อมูลนักศึกษาเรียบร้อย');
    }
    // ฟอร์มแก้ไขข้อมูลนักศึกษา
    public function edit_student($id)
    {
        $studentModel = new \App\Models\StudentModel();
        $majorModel = new \App\Models\MajorModel();

        $data['student'] = $studentModel->find($id);
        $data['majors'] = $majorModel->findAll();

        if (!$data['student'])
            return redirect()->to('admin/students')->with('error', 'ไม่พบนักศึกษา');

        return view('admin/edit_student', $data);
    }

    // บันทึกการแก้ไขนักศึกษา
    public function update_student($id)
    {
        $studentModel = new \App\Models\StudentModel();

        // Validation (รหัสนักศึกษาแก้ไม่ได้ หรือถ้าแก้ต้องเช็คซ้ำ)
        // ในที่นี้สมมติว่าให้แก้แค่ ชื่อ, สาขา, เบอร์โทร
        $studentModel->update($id, [
            'full_name' => $this->request->getPost('full_name'),
            'major_id' => $this->request->getPost('major_id'),
            'phone_number' => $this->request->getPost('phone_number')
        ]);

        return redirect()->to('admin/students')->with('success', 'แก้ไขข้อมูลนักศึกษาเรียบร้อย');
    }

    // =========================================================================
    //  ส่วนที่ 6: จัดการข้อมูลอาจารย์ (Advisor Management)
    // =========================================================================

    public function advisors()
    {
        $advisorModel = new \App\Models\AdvisorModel();
        $data['advisors'] = $advisorModel->findAll();
        return view('admin/manage_advisors', $data);
    }

    public function save_advisor()
    {
        // เช็คสิทธิ์ admin
        if (session()->get('role') != 'admin') {
            return redirect()->to('/login');
        }

        $advisorModel = new \App\Models\AdvisorModel();

        // กำหนดกฎ Validation
        $rules = [
            'username' => [
                'rules' => 'required|is_unique[advisors.username]',
                'errors' => [
                    'required' => 'กรุณากรอก Username',
                    'is_unique' => 'Username นี้มีอยู่ในระบบแล้ว (ห้ามซ้ำ)'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[4]',
                'errors' => [
                    'required' => 'กรุณากรอกรหัสผ่าน',
                    'min_length' => 'รหัสผ่านต้องอย่างน้อย 4 ตัวอักษร'
                ]
            ],
            'full_name' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->validator->getError('username')
                    ?? $this->validator->getError('password'));
        }

        // บันทึกข้อมูล
        $advisorModel->insert([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ]);

        return redirect()->to('admin/advisors')
            ->with('success', 'เพิ่มอาจารย์เรียบร้อยแล้ว');
    }

    public function delete_advisor($id)
    {
        $advisorModel = new \App\Models\AdvisorModel();
        $advisorModel->delete($id);
        return redirect()->to('admin/advisors')->with('success', 'ลบข้อมูลสำเร็จ');
    }
    // ฟอร์มแก้ไขอาจารย์
    public function edit_advisor($id)
    {
        $advisorModel = new \App\Models\AdvisorModel();
        $data['advisor'] = $advisorModel->find($id);

        if (!$data['advisor'])
            return redirect()->to('admin/advisors')->with('error', 'ไม่พบข้อมูล');

        return view('admin/edit_advisor', $data);
    }

    // บันทึกการแก้ไขอาจารย์
    public function update_advisor($id)
    {
        $advisorModel = new \App\Models\AdvisorModel();

        // เช็ค Username ซ้ำ (ยกเว้นตัวเอง)
        $username = $this->request->getPost('username');
        $rules = [
            'username' => [
                'rules' => "required|is_unique[advisors.username,advisor_id,{$id}]",
                'errors' => ['required' => 'กรอก Username', 'is_unique' => 'Username ซ้ำ']
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getError('username'));
        }

        // เตรียมข้อมูลอัปเดต
        $data = [
            'username' => $username,
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ];

        // ถ้ามีการกรอกรหัสผ่านใหม่มา ให้ Hash และบันทึก
        // ถ้าช่องรหัสผ่านว่างเปล่า แสดงว่าไม่ต้องการเปลี่ยนรหัส
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $advisorModel->update($id, $data);

        return redirect()->to('admin/advisors')->with('success', 'แก้ไขข้อมูลอาจารย์เรียบร้อย');
    }

    // =========================================================================
    //  ส่วนที่ 7: รายงานที่เกี่ยวข้องกับผู้ดูแลระบบ (1.3.1.7)
    // =========================================================================

    // 1. หน้าเมนูรวมรายงาน
    public function reports_dashboard()
    {
        return view('admin/reports/dashboard');
    }

    // 2. รายงานข้อมูลนักศึกษา (ทั้งหมด)
    public function report_students()
    {
        $studentModel = new \App\Models\StudentModel();

        // ดึงข้อมูลนักศึกษา + สาขา
        $data['students'] = $studentModel->select('students.*, majors.major_name')
            ->join('majors', 'majors.major_id = students.major_id', 'left')
            ->orderBy('student_id', 'ASC')
            ->findAll();

        // ส่งวันที่พิมพ์ไปด้วย
        $data['print_date'] = date('d/m/Y H:i');

        return view('admin/reports/report_students', $data);
    }

    // 3. รายงานสมาชิกสโมสร (คณะกรรมการ)
    public function report_committee()
    {
        $committeeModel = new \App\Models\CommitteeModel();

        // ใช้ฟังก์ชัน getCommitteeDetails ที่เราทำไว้ใน Model (Join 3 ตาราง)
        $data['committee'] = $committeeModel->getCommitteeDetails();
        $data['print_date'] = date('d/m/Y H:i');

        return view('admin/reports/report_committee', $data);
    }

    public function clear_all_students()
    {
        $db = \Config\Database::connect();

        // สั่งปิด Foreign Key Check
        $db->query('SET FOREIGN_KEY_CHECKS = 0');

        // สั่งล้างข้อมูล (Truncate)
        $db->table('activity_registrations')->truncate();
        $db->table('committee_members')->truncate();
        $db->table('students')->truncate();

        // เปิด Foreign Key Check กลับคืน
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        return redirect()->to('admin/students')->with('success', 'ล้างข้อมูลนักศึกษาทั้งหมดเรียบร้อยแล้ว!');
    }
}