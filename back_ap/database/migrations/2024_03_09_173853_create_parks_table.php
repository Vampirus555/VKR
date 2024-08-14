<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('desc');
            $table->string('short_desc', 103);
            $table->string('address', 150);
            $table->string('geolocation', 36);
            $table->string('work_time', 150);
            $table->string('website', 45);
            $table->string('phone', 17);
            $table->string('img_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parks');
    }
};
