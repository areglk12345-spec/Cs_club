<?php namespace App\Models;

use CodeIgniter\Model;

class ClubPositionModel extends Model
{
    protected $table = 'club_positions';
    protected $primaryKey = 'position_id';
    protected $allowedFields = ['position_name'];
}