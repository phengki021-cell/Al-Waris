<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heirs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();          // husband, wife, son, ...
            $table->string('name_id', 64);                 // "Suami", "Anak laki-laki", ...
            $table->string('name_ar', 64)->nullable();     // transliterasi Arab
            $table->string('slug', 64)->unique();
            $table->char('gender', 1);                     // m|f
            $table->string('category', 24);                // fard|asabah|arham|hajib
            $table->string('max_share_numerator', 8)->nullable();   // rasional n/d, hindari float
            $table->string('max_share_denominator', 8)->nullable();
            $table->text('detail')->nullable();
            $table->foreignId('source_id')->nullable()->constrained('sources')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heirs');
    }
};
