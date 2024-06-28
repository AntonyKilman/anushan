<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditModel extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'foodcity_credit_payments';
}