<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class tblinstructor extends Authenticatable implements AuthenticatableContract
{
    use AuthenticatableTrait;
    protected $table = 'tblinstructor';
    protected $primaryKey = 'instructorid';

    protected $fillable = [
        'is_Deleted', 'regularid','rankid','userid', 'suffix',
        'nickname', 'genderid', 'rankid', 'age', 'address', 'mobilenumber',
        'telephonenumber', 'civilstatusid',
        'citizenship', 'contactperson', 'contactpersonrelationship',
        'contactpersonmobilenumber', 'sss' ,'tin' ,'passportnumber',
        'passportplaceofissue', 'passportdateofissue',
        'sss', 'tin', 'pagibig', 'philhealth', 'passportexpiration',
         'bankname', 'accountname', 'accountnumber', 'datestartedinNETI', 'datetstartedinTDG'
         ,'awardsreceivedTDG'
    ];
    use HasFactory;


    public function dateofbirth()
    {
        return $this->belongsTo(user::class,'birthday');
    }

    public function rank()
    {
        return $this->belongsTo(tblrank::class,'rankid');
    }


    public function gender()
    {
        return $this->belongsTo(tblgender::class, 'genderid', 'genderid');
    }

    public function civilstatus()
    {
        return $this->belongsTo(tblcivilstatus::class, 'civilstatusid', 'civilstatusid');
    }

    public function instructorlicense()
    {
        return $this->hasMany(tblinstructorlicense::class,'instructorid', 'instructorid');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'userid', 'user_id');
    }

    public function courseschedule()
    {
        return $this->belongsTo(tblcourseschedule::class,'instructorid', 'instructorid');
    }

    public function instructordependents()
    {
        return $this->hasMany(tblinstructordependents::class,'instructorid', 'instructorid');
    }

    public function employmentinformation(){
        return $this->hasMany(tblinstructoremploymentinformation::class,'instructorid','instructorid');
    }

    public function courses(){
        return $this->hasMany(tblinstructorcourses::class, 'id', 'instructorid');
    }
}
