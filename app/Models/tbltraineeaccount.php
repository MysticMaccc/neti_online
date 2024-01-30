<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class tbltraineeaccount  extends Authenticatable implements AuthenticatableContract
{
    use AuthenticatableTrait;

    protected $table = 'tbltraineeaccount';
    protected $primaryKey = 'traineeid';
    use HasFactory;
    protected $fillable = [
        'login_attempt_count' , 'lockout_timestamp'
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


    public function certificate_name()
    {
        $middleInitial = $this->m_name ? strtoupper($this->m_name[0]) . '. ' : '';
        $suffix = $this->suffix ? ' ' . $this->suffix : '';

        $firstNameParts = explode(' ', $this->f_name);
        $formattedFirstName = '';
        foreach ($firstNameParts as $part) {
            $formattedFirstName .= ucfirst(strtolower($part)) . ' ';
        }

        $lastNameParts = explode(' ', $this->l_name);
        $formattedLastName = '';
        foreach ($lastNameParts as $part) {
            $formattedLastName .= ucfirst(strtolower($part)) . ' ';
        }

        $name = rtrim($formattedFirstName) . ' ' . $middleInitial . rtrim($formattedLastName) . $suffix;

        return $name;
    }

    public function rank()
    {
        return $this->belongsTo(tblrank::class, 'rank_id');
    }


    public function company()
    {
        return $this->belongsTo(tblcompany::class, 'company_id');
    }

    public function fleet()
    {
        return $this->belongsTo(tblfleet::class, 'fleet_id');
    }

    public function brgy()
    {
        return $this->belongsTo(refbrgy::class, 'brgyCode', 'brgyCode');
    }

    public function city()
    {
        return $this->belongsTo(refcitymun::class, 'citynumCode', 'citymunCode');
    }

    public function prov()
    {
        return $this->belongsTo(refprovince::class, 'provCode', 'provCode');
    }

    public function reg()
    {
        return $this->belongsTo(refregion::class, 'regCode', 'regCode');
    }

    public function enroled()
    {
        return $this->hasMany(tblenroled::class, 'traineeid', 'traineeid');
    }


    public function gender()
    {
        return $this->belongsTo(tblgender::class, 'genderid');
    }

    public function nationality()
    {
        return $this->belongsTo(tblnationality::class, 'nationalityid', 'nationalityid');
    }

    public function dialing_code()
    {
        return $this->belongsTo(DialingCode::class, 'dialing_code_id', 'id');
    }

    public function getBirthdayParseAttribute()
    {
        return date('F j, Y' , strtotime($this->birthday));
    }
}
