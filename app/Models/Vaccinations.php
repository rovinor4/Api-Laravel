<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccinations extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function spot()
    {
        return $this->belongsTo(Spots::class);
    }

    public function vaccine()
    {
        return $this->hasOne(Vaccines::class, "id", "vaccine_id");
    }
    public function vaccinator()
    {
        return $this->hasOne(Medicals::class, "id", "doctor_id");
    }
}
