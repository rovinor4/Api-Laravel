<?php

use App\Models\Medicals;
use App\Models\Spots;
use App\Models\Vaccines;
use App\Models\Societies;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("dose")->nullable();
            $table->date("date");
            $table->foreignIdFor(Societies::class, "society_id")->constrained();
            $table->foreignIdFor(Spots::class, "spot_id")->constrained();
            $table->foreignIdFor(Vaccines::class, "vaccine_id")->nullable()->constrained();
            $table->foreignIdFor(Medicals::class, "doctor_id")->nullable()->constrained("medicals", "id", "doctorMedicals");
            $table->foreignIdFor(Medicals::class, "officer_id")->nullable()->constrained("medicals", "id", "officerMedicals");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
