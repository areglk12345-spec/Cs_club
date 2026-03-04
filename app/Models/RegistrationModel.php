<?php
namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    // ❌ ของเดิม: protected $table = 'registrations';
    // ✅ แก้เป็น:
    protected $table = 'activity_registrations';

    protected $primaryKey = 'registration_id';
    protected $allowedFields = ['student_id', 'activity_id', 'register_date', 'status', 'checkin_time'];
}