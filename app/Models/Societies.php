<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Societies extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function Regionals()
    {
        return $this->hasOne(Regionals::class, "id", "regional_id");
    }
}
