<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'planilla_haber_id', 'paymentschedule_id', 'afp', 'spreadsheet_id', 'number', 'amount', 'checks_beneficiary_id', 'date_print', 'observations', 'status'
    ];

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function beneficiary(){
        return $this->belongsTo(ChecksBeneficiary::class, 'checks_beneficiary_id')->withTrashed();
    }
    public function spreadsheet(){
        return $this->belongsTo(Spreadsheet::class);
    }

    public function planilla_haber(){
        return $this->belongsTo(Planillahaber::class, 'planilla_haber_id', 'ID');
    }

    public function derivations(){
        return $this->hasMany(ChecksPaymentsDerivations::class, 'checks_payment_id');
    }

    public function paymentschedule(){
        return $this->belongsTo(Paymentschedule::class, 'paymentschedule_id');
    }

    public function afp_details(){
        return $this->belongsTo(Afp::class, 'afp');
    }
}
