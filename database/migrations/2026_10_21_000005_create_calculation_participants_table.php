<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calculation_id')->constrained('calculations')->cascadeOnDelete();
            $table->foreignId('heir_id')->nullable()->constrained('heirs')->nullOnDelete();
            $table->string('code', 32);                    // denormalisasi: riwayat tetap gamut jika master berubah
            $table->string('name_id', 64);
            $table->unsignedSmallInteger('count')->default(1);
            $table->json('attributes')->nullable();        // mis. {"anak_sedarah": true}
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('calculation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculation_participants');
    }
};
