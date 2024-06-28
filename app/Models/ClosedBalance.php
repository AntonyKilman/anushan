<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosedBalance extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'closed_balance';
}
