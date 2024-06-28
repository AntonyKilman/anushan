<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryEquipment extends Model
{
    use HasFactory;
    public $timestamps =true;
    public $table = 'inventory_equipment_transfer';
}
