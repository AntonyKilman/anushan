<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceProcessedRecord extends Model
{
    use HasFactory;

    protected $table = 'hr_attendance_processed_records';
    protected $primaryKey = 'id';
}
