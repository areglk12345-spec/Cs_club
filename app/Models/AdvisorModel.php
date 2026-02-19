<?php namespace App\Models;
use CodeIgniter\Model;

class AdvisorModel extends Model
{
    protected $table = 'advisors';
    protected $primaryKey = 'advisor_id';
    // ✅ ต้องมี phone ในบรรทัดนี้
    protected $allowedFields = ['username', 'password', 'full_name', 'email', 'phone'];
}