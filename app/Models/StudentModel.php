<?php namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    // ปรับให้ตรงกับ DB: student_id, password, full_name, major_id, phone_number
    protected $allowedFields = ['student_id', 'password', 'full_name', 'major_id', 'phone_number'];
    
    // ปิดการใช้ timestamp อัตโนมัติถ้าตาราง created_at เป็น default current_timestamp อยู่แล้ว
    protected $useTimestamps = false; 
}