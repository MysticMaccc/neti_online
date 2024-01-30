<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpde extends Model
{
    protected $table = "tblpde";
    protected $primaryKey ="pdeID";
    protected $fillable=[
        'requestaccountdesignation',
        'requestby',
        'requestfleet',
        'pdestatusid',
        'surname',
        'givenname',
        'middlename',
        'suffix',
        'position',
        'vessel',
        'statusid',
        'attachmentpath',
        'companyid',
        'age',
        'dateofbirth',
        'passportno',
        'passportexpdate',
        'medicalexpdate',
        'rankid'];

    public function company(){

        return $this->belongsTo(tblcompany::class,'companyid');
    }

    
    use HasFactory;
}
