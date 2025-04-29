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
            $table->enum('level', ['Healthy', 'Moderate', 'Unhealthy', 'Hazardous']); // AQI level
            $table->text('message'); // Alert message
            $table->timestamp('created_at')->useCurrent(); // Timestamp when alert was created
            $table->timestamps();

            // Foreign key constraint
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
