<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_fee_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'receipt_number',
    ];

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }
}
