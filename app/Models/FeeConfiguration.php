<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'applies_to',
        'due_date',
        'is_active',
    ];

    protected $casts = [
        'applies_to' => 'array',
        'is_active' => 'boolean',
        'due_date' => 'date',
    ];
} 