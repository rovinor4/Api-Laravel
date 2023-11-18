<?php

namespace App\Http\Controllers;

use App\Models\Societies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        try {
            $login_data = $request->validate([
                "id_card_number" => "required",
                "password" => "required"
            ]);

            $find = Societies::with("regionals")->where("id_card_number", $login_data["id_card_number"])->first();

            if ($find && Hash::check($login_data["password"], $find->password)) {

                $find->login_tokens = md5($find->id_card_number . Date::now());
                $find->save();

                return response()->json($find);
            } else {
                return response()->json(["message" => "ID Card Number or Password incorrect"], 401);
            }
        } catch (\Exception $th) {
            return response()->json(["message" => $th->getMessage()]);
        }
    }


    public function Logout(Request $request)
    {
        $dt = $request->validate(["token" => "required"]);
        $find = Societies::where("login_tokens", $dt["token"])->first();
        if ($find) {
            $find->login_tokens = null;
            $find->save();
            return response()->json(["message" => "Logout Success"]);
        }
        return response()->json(["message" => "Invalid Token"]);
    }
}
