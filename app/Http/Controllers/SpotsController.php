<?php

namespace App\Http\Controllers;

use App\Models\Spots;
use App\Models\Vaccinations;
use App\Models\Vaccines;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class SpotsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaksin = Vaccines::get();
        $data = Spots::with("Vaksin")->select(["id", "name", "address", "serve", "capacity"])->get();

        $dm_data = collect($data)->map(function ($dt) use ($vaksin) {
            $dm["id"] = $dt["id"];
            $dm["name"] = $dt["name"];
            $dm["address"] = $dt["address"];
            $dm["serve"] = $dt["serve"];
            $dm["capacity"] = $dt["capacity"];

            $koleksi = collect($dt["vaksin"]);

            foreach ($vaksin as $vl) {
                $cek = $koleksi->where("id", $vl["id"])->isEmpty();
                $dm["available_vaccines"][$vl["name"]] = !$cek ? true : false;
            }

            return $dm;
        });

        return response()->json([
            "spots" => $dm_data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $spots, Request $request)
    {

        $spo = Spots::select(["id", "name", "address", "serve", "capacity"])->where("id", $spots)->firstOrFail();

        $dt = $request->validate(["date" => "date|nullable"]);
        $date = empty($dt["date"]) ? date("Y-m-d") : $dt["date"];

        $jumlah_vaksin = Vaccinations::where("spot_id", $spo->id)->where("date", $date)->count();

        $data = [
            "date" => date("F j, Y", strtotime($date)),
            "spot" => $spo,
            "vaccinations_count" => $jumlah_vaksin
        ];

        return response()->json($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
