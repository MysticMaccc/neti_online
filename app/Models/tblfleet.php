<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblfleet extends Model
{
    protected $table = 'tblfleet';
    protected $primaryKey = 'fleetid';
    protected $fillable=['pdecertnumber'];
    use HasFactory;
}
