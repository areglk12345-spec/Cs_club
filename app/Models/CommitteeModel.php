<?php namespace App\Models;

use CodeIgniter\Model;

class CommitteeModel extends Model
{
    protected $table = 'committee_members';
    protected $primaryKey = 'committee_id';
    protected $allowedFields = ['student_id', 'academic_year_id', 'position_id'];

    // ฟังก์ชันพิเศษ: ดึงข้อมูลแบบ Join ตาราง (เพื่อให้ได้ชื่อคน, ชื่อตำแหน่ง มาแสดง)
    public function getCommitteeDetails()
    {
        return $this->select('committee_members.*, students.full_name, club_positions.position_name, academic_years.year_name')
                    ->join('students', 'students.student_id = committee_members.student_id')
                    ->join('club_positions', 'club_positions.position_id = committee_members.position_id')
                    ->join('academic_years', 'academic_years.year_id = committee_members.academic_year_id')
                    ->orderBy('academic_years.year_name', 'DESC')
                    ->findAll();
    }
}