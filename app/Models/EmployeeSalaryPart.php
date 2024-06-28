<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryPart extends Model
{
    use HasFactory;
    protected $table = 'hr_employee_salary_parts';
    protected $primaryKey = 'id';
}
