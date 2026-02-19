<?php namespace App\Models;

use CodeIgniter\Model;

class AcademicYearModel extends Model
{
    protected $table = 'academic_years';
    protected $primaryKey = 'year_id';
    protected $allowedFields = ['year_name', 'is_current'];
}