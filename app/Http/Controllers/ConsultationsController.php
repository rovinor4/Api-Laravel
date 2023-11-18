<?php

namespace App\Http\Controllers;

use App\Models\Consultations;
use App\Models\Societies;
use Illuminate\Http\Request;

class ConsultationsController extends Controller
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

            $FindSoc = Societies::where("login_tokens", $request->token)->firstOrFail();

            $dt = $request->validate(["disease_history" => "required", "current_symptoms" => "required"]);
            $dt["status"] = "pending";
            $dt["societies_id"] = $FindSoc->id;

            Consultations::create($dt);


            return response()->json([
                "message" => "Request consultation sent successful"
            ]);
        } catch (\Exception $ms) {
            return response()->json([
                "message" => $ms->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $FindSoc = Societies::where("login_tokens", $request->token)->firstOrFail();

        $dt = Consultations::with("doctor")->select(["id", "status", "disease_history", "current_symptoms", "doctor_note", "doctor_id"])->where("societies_id", $FindSoc->id)->firstOrFail();
        data_forget($dt, "doctor_id");
        return response()->json(["consultation" => $dt]);
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
