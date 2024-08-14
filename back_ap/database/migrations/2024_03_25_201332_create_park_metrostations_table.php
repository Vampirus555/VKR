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
        Schema::create('park_metrostations', function (Blueprint $table) {
            $table->unsignedBigInteger('park_id')->index();
            $table->unsignedBigInteger('metrostation_id')->index();
            $table->primary(['park_id', 'metrostation_id']);
            $table->foreign('park_id')->references('id')->on('parks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('metrostation_id')->references('id')->on('metrostations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('park_metrostations');
    }
};
