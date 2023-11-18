<?php

namespace App\Http\Controllers;

use App\Models\Consultations;
use Carbon\Carbon;
use App\Models\Societies;
use App\Models\Vaccinations;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $valid = $request->validate([
                "date" => "date|date_format:Y-m-d|required",
                "spot_id" => "required|exists:spots,id"
            ]);
            $FindSoc = Societies::where("login_tokens", $request->token)->firstOrFail();
            $valid["society_id"] = $FindSoc->id;

            //cek izin dari dokter
            $konsultasi = Consultations::where("societies_id", $FindSoc->id)->where("status", "accepted")->count();
            if ($konsultasi == 0) {
                return response()->json(['message' => 'Your consultation must be accepted by doctor before'], 401);
            }
            // Validasi Tanggal Vaksin 1
            $vaksin = Vaccinations::where("society_id", $FindSoc->id);
            $vk1 = $vaksin->first();
            if ($vk1) {
                $validTanggal = Carbon::parse($vk1->date);
                $tambahTanggal = $validTanggal->addDays(30);

                if (now() < $tambahTanggal) {
                    return response()->json(['message' => 'Wait at least +30 days from 1st Vaccination'], 401);
                }
            }

            // Cek 2 vaksin
            $jumlah = $vaksin->count();
            if ($jumlah >= 2) {
                return response()->json(['message' => 'Society has been 2x vaccinated'], 401);
            }
            Vaccinations::create($valid);

            $text = $jumlah === 1 ? "Second" : "First";
            return response()->json(["message" => "$text vaccination registered successful"]);
        } catch (\Illuminate\Validation\ValidationException $ms) {
            return response()->json(
                [
                    "message" => "Invalid field",
                    "errors" => $ms->errors()
                ],
                401
            );
        }
    }

    /**
     * Display the specified resource.
     */

    public function GetAntrian(string $tanggal, $id, $spot)
    {
        $urutan = Vaccinations::where("date", $tanggal)->where("spot_id", $spot)->orderBy('id')->pluck('id')->search($id);
        if ($urutan !== false) {
            $urutan++;
            return $urutan;
        } else {
            return null;
        }
    }

    public function show(Request $request)
    {
        $FindSoc = Societies::where("login_tokens", $request->token)->firstOrFail();

        $data = Vaccinations::with(["spot.regional", "vaccine", "vaccinator"])->where("society_id", $FindSoc->id)->get();

        $result = [];
        // return response()->json($data[0]);

        foreach ($data as $key => $value) {
            $dtk = $key === 0 ? "first" : "second";

            $result[$dtk]["queue"] = $this->GetAntrian($value["date"], $value["id"], $value["spot_id"]);
            $result[$dtk]["dose"] = $value["dose"];
            $result[$dtk]["vaccination_date"] = $value["date"];
            $result[$dtk]["spot"]["id"] = $value["spot"]["id"];
            $result[$dtk]["spot"]["name"] = $value["spot"]["name"];
            $result[$dtk]["spot"]["address"] = $value["spot"]["address"];
            $result[$dtk]["spot"]["serve"] = $value["spot"]["serve"];
            $result[$dtk]["spot"]["capacity"] = $value["spot"]["capacity"];
            $result[$dtk]["spot"]["regional"]["id"] = $value["spot"]["regional"]["id"];
            $result[$dtk]["spot"]["regional"]["province"] = $value["spot"]["regional"]["province"];
            $result[$dtk]["spot"]["regional"]["district"] = $value["spot"]["regional"]["district"];
            $result[$dtk]["status"] = empty($value["doctor_id"]) ? null : "done";
            $result[$dtk]["vaccine"] = $value["vaccine"];
            $result[$dtk]["vaccinator"] = $value["vaccinator"];
        }

        if ($data->count() === 1) {
            $result["second"] = null;
        }

        return $result;
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
