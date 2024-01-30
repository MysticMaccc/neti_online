<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcompanycourse extends Model
{
    protected $table = 'tblcompanycourse';
    protected $primaryKey = 'companycourseid';
    protected $fillable = ['companyid','courseid','ratepeso','rateusd'];
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(tblcompany::class, 'companyid');
    }

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'courseid');
    }
}
