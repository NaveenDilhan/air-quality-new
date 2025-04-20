<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('aqi_data', function (Blueprint $table) {
        $table->id(); // Primary key
        $table->unsignedBigInteger('sensor_id'); // Foreign key to sensors table
        $table->decimal('co2_level', 8, 2); // CO2 level reading
        $table->decimal('o2_level', 8, 2); // O2 level reading
        $table->decimal('pm25_level', 8, 2); // PM2.5 level reading
        $table->decimal('pm10_level', 8, 2); // PM10 level reading
        $table->decimal('no2_level', 8, 2); // NO2 level reading
        $table->decimal('so2_level', 8, 2); // SO2 level reading
        $table->decimal('aqi', 8, 2); // AQI value calculated from sensor readings
        $table->timestamp('recorded_at'); // Timestamp of when the data was recorded
        $table->timestamps();

        // Foreign key constraint to sensors table
        $table->foreign('sensor_id')->references('id')->on('sensors')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aqi_data');
    }
};
