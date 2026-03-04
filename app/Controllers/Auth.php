<?php
namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\MajorModel;

class Auth extends BaseController
{
    // -------------------------------------------------------------------------
    //  หน้า Login
    // -------------------------------------------------------------------------
    public function fix_db()
    {
        $db = \Config\Database::connect();
        try {
            $db->query("ALTER TABLE activity_registrations ADD COLUMN checkin_time DATETIME NULL AFTER status;");
            echo "activity_registrations updated. ";
        } catch (\Exception $e) {
            echo "Reg table fail: " . $e->getMessage() . ". ";
        }

        try {
            $db->query("ALTER TABLE activities ADD COLUMN latitude DECIMAL(10,8) NULL, ADD COLUMN longitude DECIMAL(11,8) NULL, ADD COLUMN qr_token VARCHAR(100) NULL;");
            echo "activities updated. ";
        } catch (\Exception $e) {
            echo "Act table fail: " . $e->getMessage() . ". ";
        }

        echo "Done.";
    }

    public function index()
    {
        if (session()->get('is_logged_in')) {
            if (session()->get('role') == 'admin') {
                return redirect()->to('/admin/dashboard');
            } elseif (session()->get('role') == 'committee') {
                return redirect()->to('/committee/dashboard');
            } elseif (session()->get('role') == 'advisor') { // ✅ เพิ่มส่วนของอาจารย์
                return redirect()->to('/advisor/dashboard');
            }
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    // -------------------------------------------------------------------------
    //  ประมวลผลการ Login
    // -------------------------------------------------------------------------
    public function process()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');

        // 1. กรณีเป็น นักศึกษา หรือ กรรมการสโมสร
        if ($role == 'student' || $role == 'committee') {

            $model = new StudentModel();
            $user = $model->where('student_id', $username)->first();

            if ($user) {
                $passVerify = password_verify($password, $user['password']);
                if (!$passVerify && $password === $user['password']) {
                    $passVerify = true;
                }

                if ($passVerify) {
                    $db = \Config\Database::connect();
                    $isCommittee = $db->table('committee_members')
                        ->where('student_id', $user['student_id'])
                        ->countAllResults() > 0;

                    if ($role == 'committee' && !$isCommittee) {
                        return redirect()->back()->with('msg', 'คุณไม่มีสิทธิ์เข้าใช้งานในฐานะกรรมการสโมสร');
                    }

                    $session->set([
                        'user_id' => $user['student_id'],
                        'student_id' => $user['student_id'],
                        'user_name' => $user['full_name'], // ✅ สำหรับ Layout
                        'full_name' => $user['full_name'],
                        'avatar' => $user['avatar'] ?? null,     // ✅ เพิ่มรูป (กันพังถ้าไม่มีคีย์)
                        'role' => ($role == 'committee') ? 'committee' : 'student',
                        'is_committee' => $isCommittee,
                        'is_logged_in' => true
                    ]);

                    return ($role == 'committee') ? redirect()->to('committee/dashboard') : redirect()->to('dashboard');
                } else {
                    return redirect()->back()->with('msg', 'รหัสผ่านไม่ถูกต้อง');
                }
            } else {
                return redirect()->back()->with('msg', 'ไม่พบรหัสนักศึกษานี้ในระบบ');
            }
        }

        // ✅ 2. กรณีเป็น อาจารย์ที่ปรึกษา (Advisor) - เพิ่มใหม่
        elseif ($role == 'advisor') {
            $db = \Config\Database::connect();
            $user = $db->table('advisors')->where('username', $username)->get()->getRowArray();

            if ($user) {
                // เช็ครหัสผ่าน (รองรับทั้ง Hash และธรรมดา)
                $passVerify = password_verify($password, $user['password']);
                if (!$passVerify && $password === $user['password']) {
                    $passVerify = true;
                }

                if ($passVerify) {
                    $session->set([
                        'user_id' => $user['advisor_id'],
                        'full_name' => $user['full_name'],
                        'role' => 'advisor',
                        'is_logged_in' => true
                    ]);
                    return redirect()->to('advisor/dashboard');
                } else {
                    return redirect()->back()->with('msg', 'รหัสผ่านอาจารย์ไม่ถูกต้อง');
                }
            } else {
                return redirect()->back()->with('msg', 'ไม่พบชื่อผู้ใช้งานอาจารย์ในระบบ');
            }
        }

        // 3. กรณีเป็น Admin
        elseif ($role == 'admin') {
            $db = \Config\Database::connect();
            $user = $db->table('admins')->where('username', $username)->get()->getRowArray();

            $passVerify = ($user && password_verify($password, $user['password']));
            if (!$passVerify && $user && $password === $user['password']) {
                $passVerify = true;
            }

            if ($user && $passVerify) {
                $session->set([
                    'user_id' => $user['admin_id'],
                    'role' => 'admin',
                    'is_logged_in' => true
                ]);
                return redirect()->to('admin/dashboard');
            }
            return redirect()->back()->with('msg', 'ชื่อผู้ใช้หรือรหัสผ่าน Admin ไม่ถูกต้อง');
        }

        return redirect()->back()->with('msg', 'กรุณาเลือกสถานะผู้ใช้งานให้ถูกต้อง');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // -------------------------------------------------------------------------
    //  หน้าสมัครสมาชิก (Register)
    // -------------------------------------------------------------------------
    public function register()
    {
        $majorModel = new MajorModel();
        $data['majors'] = $majorModel->findAll();
        return view('auth/register', $data);
    }

    public function save_register()
    {
        $rules = [
            'student_id' => 'required|min_length[10]|max_length[20]|is_unique[students.student_id]',
            'full_name' => 'required|min_length[3]',
            'major_id' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[4]',
            'pass_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $studentModel = new StudentModel();
        $data = [
            'student_id' => $this->request->getPost('student_id'),
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'major_id' => $this->request->getPost('major_id'),
            'phone_number' => $this->request->getPost('phone_number'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];
        $studentModel->insert($data);

        return redirect()->to('/login')->with('success', 'ลงทะเบียนสำเร็จ! กรุณาเข้าสู่ระบบ');
    }

    public function setup_admin()
    {
        $db = \Config\Database::connect();
        $db->table('admins')->where('username', 'admin')->delete();
        $db->table('admins')->insert([
            'username' => 'admin',
            'password' => password_hash('1234', PASSWORD_DEFAULT),
            'full_name' => 'ผู้ดูแลระบบ (System Admin)'
        ]);
        echo "สร้าง Admin สำเร็จ! (User: admin / Pass: 1234)";
    }
}