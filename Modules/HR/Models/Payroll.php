<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $fillable = [
        'staff_id', 'basic_salary', 'allowances', 'bonuses', 'deductions', 'net_salary', 'payroll_period', 'status', 'payslip_path'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
} 