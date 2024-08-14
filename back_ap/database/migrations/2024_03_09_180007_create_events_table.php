<?php

use App\Models\Park;
use App\Models\Organizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы в бД.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Park::class);
            $table->foreignIdFor(Organizer::class)->nullable();
            $table->string('name', 100);
            $table->enum('status', ['одобрено', 'отклонено', 'на рассмотрении']);
            $table->enum('type', ['временное', 'постоянное']);
            $table->string('limit_age', 10);
            $table->text('desc');
            $table->string('short_desc', 103);
            $table->date('start_date')->nullable();;
            $table->date('end_date')->nullable();;
            $table->text('schedule');
            $table->string('price', 25)->nullable();;
            $table->string('phone', 17);
            $table->string('img_path')->nullable();
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
