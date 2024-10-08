<?php

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
        Schema::create('weather_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->decimal("temperature");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_records');
    }
};
