<?php namespace App\Models;

use CodeIgniter\Model;

class MajorModel extends Model
{
    protected $table = 'majors';
    protected $primaryKey = 'major_id';
    protected $allowedFields = ['major_name'];
}