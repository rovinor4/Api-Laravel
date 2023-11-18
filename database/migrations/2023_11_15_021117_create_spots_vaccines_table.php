<?php

use App\Models\Spots;
use App\Models\Vaccines;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spots_vaccines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Spots::class, "spot_id")->constrained();
            $table->foreignIdFor(Vaccines::class, "vaccine_id")->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spots_vaccines');
    }
};
