<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'applies_to',
        'is_active',
    ];

    protected $casts = [
        'applies_to' => 'array',
        'is_active' => 'boolean',
    ];
} 