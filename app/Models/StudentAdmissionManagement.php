<?php

namespace App\Models;

use App\Traits\TracksUserActions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'admission_no',
    'mobile',
    'email',
    'class_id',
    'address',
    'father_name',
    'mother_name',
    'profile_image',
    'documents',
    'admission_status',
    'created_by',
    'updated_by'
])]

class StudentAdmissionManagement extends Model
{
    use SoftDeletes, TracksUserActions;

    protected $table = 'student_admission_management';

    public function studentClasses()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'id');
    }
}
