<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayable extends Model
{
    use HasFactory;

    protected $table = 'hr_salary_payables';
    protected $primaryKey = 'id';
}
