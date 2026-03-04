<?php
namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table = 'activities';
    protected $primaryKey = 'activity_id';
    // ปรับให้ตรงกับ DB
    protected $allowedFields = [
        'activity_name',
        'cover_image',
        'description',
        'start_date',
        'end_date',
        'location',
        'academic_year_id',
        'created_by_committee',
        'advisors_id',
        'status',
        'latitude',
        'longitude',
        'qr_token'
    ];
}