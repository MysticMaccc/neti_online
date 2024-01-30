<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbldormitoryreservation extends Model
{
    use Authenticatable;
    use HasFactory;
    public $table = 'tbldormitoryreservation';

    public function paymentmode()
    {
        return $this->belongsTo(tblpaymentmode::class, 'paymentmodeid', 'paymentmodeid');
    }

    public function enroled(){
        return $this->belongsTo(tblenroled::class, 'enroledid', 'enroledid');
    }

    
}
