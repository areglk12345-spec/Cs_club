<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------------------------
// 1. เส้นทางหลัก (Login / Register)
// --------------------------------------------------------------------
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('auth/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('register/save', 'Auth::save_register');
$routes->get('seed/create_admin', 'Seed::create_admin');
$routes->get('setup_admin', 'Auth::setup_admin');

// เส้นทางสแกน QR Code เพื่อเช็คชื่อ
$routes->get('scan/(:num)/(:segment)', 'Scan::index/$1/$2');

// --------------------------------------------------------------------
// 2. เส้นทางนักศึกษา (ใช้ UI เดิมตามที่คุณต้องการ)
// --------------------------------------------------------------------
// เรียกใช้ Dashboard::index เพื่อให้ได้หน้า UI เดิม
$routes->get('dashboard', 'Dashboard::index');

// หน้าประวัติกิจกรรม
$routes->get('student/history', 'Student::history');

// กลุ่มเส้นทางกิจกรรม (แยกออกมาอยู่นอก Admin แล้ว!)
$routes->group('student', function ($routes) {
    $routes->get('activity/detail/(:num)', 'Student::activity_detail/$1');
    $routes->post('activity/register', 'Student::register_activity');
});

// --------------------------------------------------------------------
// 3. เส้นทางผู้ดูแลระบบ (ADMIN)
// --------------------------------------------------------------------
$routes->group('admin', function ($routes) {

    $routes->get('dashboard', 'Admin::dashboard');

    // จัดการสาขา
    $routes->get('majors', 'Admin::majors');
    $routes->post('majors/save', 'Admin::save_major');
    $routes->get('majors/delete/(:num)', 'Admin::delete_major/$1');

    $routes->get('major/edit/(:num)', 'Admin::edit_major/$1');
    $routes->post('major/update/(:num)', 'Admin::update_major/$1');

    $routes->get('major/delete/(:num)', 'Admin::delete_major/$1');   // ✅ ตัวที่แก้ Error ของคุณ
    $routes->get('majors/delete/(:num)', 'Admin::delete_major/$1');  // เผื่อไว้

    // จัดการกรรมการ
    $routes->get('committee', 'Admin::committee');
    $routes->post('committee/save', 'Admin::save_committee');
    $routes->get('committee/edit/(:num)', 'Admin::edit_committee/$1');
    $routes->post('committee/update/(:num)', 'Admin::update_committee/$1');
    $routes->get('committee/delete/(:num)', 'Admin::delete_committee/$1');


    // จัดการปีการศึกษา
    $routes->get('years', 'Admin::years');
    $routes->post('years/save', 'Admin::save_year');
    $routes->get('years/delete/(:num)', 'Admin::delete_year/$1');
    $routes->get('years/set_current/(:num)', 'Admin::set_current_year/$1');

    $routes->get('years/edit/(:num)', 'Admin::edit_year/$1');
    $routes->post('years/update/(:num)', 'Admin::update_year/$1');

    // แบบไม่มี s (year) - ✅ ตัวนี้คือตัวที่แก้ Error POST ของคุณ
    $routes->get('year/edit/(:num)', 'Admin::edit_year/$1');
    $routes->post('year/update/(:num)', 'Admin::update_year/$1');

    // จัดการตำแหน่ง
    $routes->get('positions', 'Admin::positions');
    $routes->post('positions/save', 'Admin::save_position');
    $routes->get('positions/delete/(:num)', 'Admin::delete_position/$1');

    $routes->get('positions/edit/(:num)', 'Admin::edit_position/$1');
    $routes->post('positions/update/(:num)', 'Admin::update_position/$1');

    // (เผื่อไว้) รองรับ URL แบบไม่มี s
    $routes->get('position/edit/(:num)', 'Admin::edit_position/$1');
    $routes->post('position/update/(:num)', 'Admin::update_position/$1');

    // จัดการนักศึกษา
    $routes->get('students', 'Admin::students');
    $routes->get('students/reset/(:num)', 'Admin::reset_student_pass/$1');
    $routes->get('students/delete/(:num)', 'Admin::delete_student/$1');

    $routes->get('students/edit/(:any)', 'Admin::edit_student/$1');
    $routes->post('students/update/(:any)', 'Admin::update_student/$1');

    // แบบไม่มี s (student) -> เผื่อไว้
    $routes->get('student/edit/(:any)', 'Admin::edit_student/$1');
    $routes->post('student/update/(:any)', 'Admin::update_student/$1');

    // จัดการอาจารย์
    $routes->get('advisors', 'Admin::advisors');
    $routes->post('advisors/save', 'Admin::save_advisor');
    $routes->get('advisors/delete/(:num)', 'Admin::delete_advisor/$1');

    $routes->get('advisors/edit/(:num)', 'Admin::edit_advisor/$1');
    $routes->post('advisors/update/(:num)', 'Admin::update_advisor/$1');

    // แบบไม่มี s (advisor) -> เผื่อไว้
    $routes->get('advisor/edit/(:num)', 'Admin::edit_advisor/$1');
    $routes->post('advisor/update/(:num)', 'Admin::update_advisor/$1');

    // ระบบรายงาน
    $routes->get('reports', 'Admin::reports_dashboard');
    $routes->get('reports/students', 'Admin::report_students');
    $routes->get('reports/committee', 'Admin::report_committee');

}); // ✅ ปีกกาปิดของ Admin ต้องจบตรงนี้ (ห้ามมี student ต่อท้ายข้างใน)

// เส้นทางกิจกรรมสำหรับนักศึกษา
$routes->group('student', function ($routes) {
    $routes->get('activity/detail/(:num)', 'Student::activity_detail/$1');
    $routes->post('activity/register', 'Student::register_activity');

    $routes->get('profile', 'Student::profile');
    $routes->post('profile/update', 'Student::update_profile');

    // ✅ เพิ่มส่วนประเมินผล
    $routes->get('feedback/(:num)', 'Student::feedback/$1');
    $routes->post('feedback/save', 'Student::save_feedback');
});


$routes->group('committee', function ($routes) {

    // 1. หน้าหลัก Dashboard
    $routes->get('dashboard', 'Committee::index');

    // 2. จัดการข้อมูลสมาชิกสโมสร (1.3.2.1)
    $routes->get('members', 'Committee::members');

    // 3. จัดการข้อมูลกิจกรรม (1.3.2.2) - CRUD
    $routes->get('activities', 'Committee::activities');            // ดูรายการ
    $routes->get('activity/create', 'Committee::create_activity');  // ฟอร์มเพิ่ม
    $routes->post('activity/save', 'Committee::save_activity');     // บันทึกเพิ่ม
    $routes->get('activity/edit/(:num)', 'Committee::edit_activity/$1'); // ฟอร์มแก้
    $routes->post('activity/update/(:num)', 'Committee::update_activity/$1'); // บันทึกแก้
    $routes->get('activity/delete/(:num)', 'Committee::delete_activity/$1');  // ลบ

    // 4. ตรวจสอบการเข้าร่วม (1.3.2.3) - เช็คชื่อ
    $routes->get('check_participation', 'Committee::participation_list');
    $routes->get('participation/(:num)', 'Committee::participants/$1'); // หน้าเช็คชื่อ (ใช้ฟังก์ชันเดิมได้)
    $routes->get('status/(:num)/(:segment)', 'Committee::update_status/$1/$2');

    // 5. สรุปรายงาน (1.3.2.4 & 1.3.2.5)
    $routes->get('reports', 'Committee::reports');

    $routes->get('activity/cancel/(:num)', 'Committee::cancel_activity/$1'); // เพิ่มบรรทัดนี้
});

// --- เส้นทางสำหรับอาจารย์ที่ปรึกษา (Advisor) ---
$routes->group('advisor', function ($routes) {
    $routes->get('dashboard', 'Advisor::dashboard');
    $routes->get('check_activities', 'Advisor::check_activities');
    $routes->get('approve/(:num)', 'Advisor::approve_activity/$1');
    $routes->get('reports', 'Advisor::reports');
    $routes->get('report_participants/(:num)', 'Advisor::report_participants/$1');
    // ... เส้นทางเดิม ...
    $routes->get('reject/(:num)', 'Advisor::reject_activity/$1'); // เพิ่มบรรทัดนี้
});
