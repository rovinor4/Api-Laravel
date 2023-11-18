<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spots extends Model
{
    use HasFactory;


    public function Vaksin()
    {
        return $this->belongsToMany(Vaccines::class, "spots_vaccines", "spot_id", "vaccine_id");
    }

    public function regional()
    {
        return $this->belongsTo(Regionals::class);
    }
}
