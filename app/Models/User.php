<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'f_name',
        'm_name',
        'l_name',
        'email',
        'password_tip',
        'password',
        'u_type',
        'is_active',
        'contact_num',
        'hash_id',
        'dep_type',
        'user_id',
        'suffix',
        'birthday',
        'birthplace',
        'regCode',
        'provCode',
        'citynumCode',
        'brgyCode',
        'street',
        'postal',
        'imagepath',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function formal_name()
    {
        $middleInitial = $this->m_name ? $this->m_name[0] : '';
        $suffix = $this->suffix ? ' ' . $this->suffix : '';

        $name = $this->l_name . ', ' . $this->f_name . ' ' . $middleInitial . $suffix;

        if ($this->m_name) {
            $name .= '.';
        }

        return $name;
    }

    public function getInstructorNameAttribute()
    {
        if($this->user_id != 93){
            $middleInitial = $this->m_name ? $this->m_name[0] : '';
            $suffix = $this->suffix ? ' ' . $this->suffix : '';
    
            $name = $this->l_name . ', ' . $this->f_name . ' ' . $middleInitial . $suffix;
    
            if ($this->m_name) {
                $name .= '.';
            }
        return $name;

        } else {
            $name = '';
        return $name;

        }

    }

    public function dialing_code()
    {
        return $this->belongsTo(DialingCode::class, 'dialing_code_id', 'id');
    }

    public function adminroles()
    {
        return $this->hasMany(adminroles::class , 'user_id' , 'user_id');
    }

    public function rank()
    {
        return $this->belongsTo(tblrank::class, 'rank_id');
    }

    // Trainee model
    public function enrollments()
    {
        return $this->hasMany(tblenroled::class, 'traineeid', 'traineeid');
    }

    public function instructor()
    {
        return $this->belongsTo(tblinstructor::class, 'user_id', 'userid');
    }

    public function instructorOne()
    {
        return $this->hasOne(tblinstructor::class, 'userid');
    }

    public function region()
    {
        return $this->belongsTo(refregion::class,'regCode', 'id');
    }

    public function province()
    {
        return $this->belongsTo(refprovince::class,'provCode', 'id');
    }

    public function citymun()
    {
        return $this->belongsTo(refcitymun::class,'citynumCode', 'id');
    }

    public function barangay()
    {
        return $this->belongsTo(refbrgy::class,'brgyCode', 'id');
    }

    public function attachment(){
        return $this->hasMany(tblinstructorattachment::class, 'userid');
    }

    public function company(){
        return $this->belongsTo(tblcompany::class, 'company_id','companyid');
    }
}
