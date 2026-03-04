<?php
namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table = 'activity_feedback';
    protected $primaryKey = 'feedback_id';
    protected $allowedFields = [
        'activity_id',
        'student_id',
        'rating',
        'comment',
        'created_at'
    ];
}
