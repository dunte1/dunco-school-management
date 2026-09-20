<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $table = 'fees';
    
    protected $fillable = [
        'name',
        'amount',
        'description',
        'fee_category_id',
        'fee_type_id',
    ];

    public function category()
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }

    public function type()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }

    public function programs()
    {
        return $this->belongsToMany(\Modules\Academic\Models\Subject::class, 'fee_program');
    }

    public function students()
    {
        return $this->belongsToMany(\Modules\Academic\Models\Student::class, 'fee_student')->withPivot('paid');
    }
} 