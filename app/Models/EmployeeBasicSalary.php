<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBasicSalary extends Model
{
    use HasFactory;

    protected $table = 'hr_employee_basic_salaries';
    protected $primaryKey = 'id';
}
