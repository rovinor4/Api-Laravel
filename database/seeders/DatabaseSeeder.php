<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Regionals;
use App\Models\Societies;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Regionals::insert([
            [
                "province" => "Jawa Timur",
                "district" => "Mojokerto"
            ]
        ]);


        Societies::insert([
            [
                "id_card_number" => "00520194",
                "password" => Hash::make("12345"),
                "name" => "Rovino Ramadhani",
                "born_date" => "2004-5-20",
                "gender" => "male",
                "address" => "Jln Raya Tendean RT 01 Rw 01",
                "regional_id" => 1
            ]
        ]);
    }
}
