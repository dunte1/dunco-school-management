<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class GradingScale extends Model
{
    protected $table = 'grading_scales';

    protected $fillable = ['name', 'description'];

    public function grades()
    {
        return $this->hasMany(Grade::class, 'grading_scale_id');
    }
}
