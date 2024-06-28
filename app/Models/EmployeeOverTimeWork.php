<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOverTimeWork extends Model
{
    use HasFactory;

    protected $table = 'hr_employee_over_time_works';
    protected $primaryKey = 'id';
}
