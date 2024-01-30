<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpdereq extends Model
{
    protected $table = 'tblpdereq';
    protected $primaryKey = 'pderequirementsid';
    protected $fillable=['rankid','pderequirements','if_any','deletedid'];
    
    use HasFactory;
}
