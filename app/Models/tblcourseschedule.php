<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcourseschedule extends Model
{
    protected $table = 'tblcourseschedule';
    protected $primaryKey = 'scheduleid';
    use HasFactory;

    protected $fillable = [
        'batchno',
        'courseid',
        'coursecode',
        'startdateformat',
        'enddateformat',
        'dateonsitefrom',
        'dateonsiteto',
        'dateonlinefrom',
        'dateonlineto',
    ];

    public function course()
    {
        return $this->belongsTo(tblcourses::class,'courseid', 'courseid');
    }

    public function instructor()
    {
        return $this->belongsTo(tblinstructor::class,'instructorid','userid');
    }

    public function altinstructor()
    {
        return $this->belongsTo(tblinstructor::class,'alt_instructorid','userid');
    }

    public function assessor()
    {
        return $this->belongsTo(tblinstructor::class,'assessorid', 'userid');
    }

    public function altassessor()
    {
        return $this->belongsTo(tblinstructor::class,'alt_assessorid', 'userid');
    }

    public function room()
    {
        return $this->belongsTo(tblroom::class,'roomid');
    }

    public function count_enrolled()
    {
        return $this->hasMany((tblenroled::class));
    }

    public function ins_license()
    {
        return $this->belongsTo(tblinstructorlicense::class, 'instructorlicense', 'instructorlicense');
    }

    public function asses_license()
    {
        return $this->belongsTo(tblinstructorlicense::class, 'assessorlicense','instructorlicense');
    }

    public function enroll()
    {
        return $this->hasMany(tblenroled::class, 'scheduleid', 'scheduleid');
    }

}
