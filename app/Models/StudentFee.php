<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'fee_category_id',
        'amount',
        'paid_amount',
        'due_date',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function category()
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function studentAdmission()
    {
        return $this->belongsTo(StudentAdmissionManagement::class, 'student_id', 'user_id');
    }
}
