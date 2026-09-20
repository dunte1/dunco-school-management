<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplicationNote extends Model
{
    protected $fillable = ['admission_application_id', 'user_id', 'note'];

    public function application() { return $this->belongsTo(AdmissionApplication::class, 'admission_application_id'); }
    public function user() { return $this->belongsTo(\App\Models\User::class); }
}
