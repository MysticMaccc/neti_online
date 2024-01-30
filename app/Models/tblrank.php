<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblrank extends Model
{
    protected $table = 'tblrank';
    protected $primaryKey = 'rankid';
    protected $fillable=['rank','rankacronym','ranklevelid','rankdepartmentid','deletedid'];

    use HasFactory;

    public function ranklevel(){
       
        return $this->belongsTo(tblranklevel::class,'ranklevelid');
    }

    public function rankdepartment(){
       
        return $this->belongsTo(tblrankdepartment::class,'rankdepartmentid');
    }
}
