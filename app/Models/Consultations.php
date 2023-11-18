<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultations extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function doctor()
    {
        return $this->hasOne(Medicals::class, "id", "doctor_id");
    }
}
