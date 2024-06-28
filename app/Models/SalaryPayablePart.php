<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayablePart extends Model
{
    use HasFactory;

    protected $table = 'hr_salary_payable_parts';
    protected $primaryKey = 'id';
}
