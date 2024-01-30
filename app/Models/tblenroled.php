<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblenroled extends Model
{
    protected $table = 'tblenroled';
    protected $primaryKey = 'enroledid';
    protected $fillable = ['pendingid', 'deletedid'];
    use HasFactory;

    public function schedule()
    {   
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid');
    }

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'courseid');
    }

    public function trainee()
    {
        return $this->belongsTo(tbltraineeaccount::class, 'traineeid', 'traineeid');
    }

    public function payment()
    {
        return $this->belongsTo(tblpaymentmode::class, 'paymentmodeid');
    }

    public function dorm()
    {
        return $this->belongsTo(tbldorm::class, 'dormid');
    }

    public function attendance()
    {
        return $this->hasMany(tblscheduleattendance::class);
    }

    public function certificate()
    {
        return $this->belongsTo(tblcertificatehistory::class, 'traineeid', 'traineeid');
    }

    public function cln()
    {
        return $this->belongsTo(tblclntype::class, 'cln_id');
    }

    public function dormitory()
    {
        return $this->hasMany(tbldormitoryreservation::class, 'enroledid', 'enroledid');
    }

    public function reservationstatus(){
        return $this->belongsTo(tblreservationstatus::class,'reservationstatusid', 'id');
    }
    
    public function bus()
    {
        return $this->belongsTo(tblbusmode::class, 'busmodeid');
    }

    public function tshirt()
    {
        return $this->belongsTo(tbltshirt::class, 'tshirtid');
    }

    public function old_schedule()
    {
        return $this->belongsTo(tblremedial::class, 'enroledid','enroledid');
    }

    public function tper_rating()
    {
        return $this->hasMany(Tper_evaluation_rating::class , 'enroled_id', 'enroledid');
    }
}
