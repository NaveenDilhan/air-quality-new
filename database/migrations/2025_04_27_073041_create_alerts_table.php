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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aqi_data_id'); // Foreign key to AQI data
            $table->string('alert_level'); // 'Healthy', 'Moderate', 'Unhealthy', 'Hazardous'
            $table->text('message'); // Alert message
            $table->timestamps();

            // Foreign key constraint to aqi_data table
            $table->foreign('aqi_data_id')->references('id')->on('aqi_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
