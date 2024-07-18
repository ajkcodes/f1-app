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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_id')->constrained()->onDelete('cascade');
            $table->string('number');
            $table->string('position');
            $table->string('positionText');
            $table->string('points');
            $table->string('driverId');
            $table->string('constructorId');
            $table->string('grid');
            $table->string('laps');
            $table->string('status');
            $table->string('time_millis')->nullable();
            $table->string('time')->nullable();
            $table->timestamps();

            $table->foreign('driverId')->references('driverId')->on('drivers')->onDelete('cascade');
            $table->foreign('constructorId')->references('constructorId')->on('constructors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
