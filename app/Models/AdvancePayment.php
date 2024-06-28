<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    use HasFactory;

    protected $table = 'hr_advance_payments';
    protected $primaryKey = 'id';
}
