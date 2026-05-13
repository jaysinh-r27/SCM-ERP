<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'status',
    ];

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }
}
