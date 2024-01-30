<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcompany extends Model
{
    protected $table = 'tblcompany';
    protected $primaryKey = 'companyid';
    
    protected $fillable = ['company', 'designation', 'addressline1', 'addressline2', 'addressline3', 'position', 'busprice', 'deletedid'];

    use HasFactory;

    public function bank()
    {
        return $this->belongsTo(tblbillingaccount::class, 'defaultBank_id', 'billingaccountid');
    }
}
