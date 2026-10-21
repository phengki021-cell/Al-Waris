<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculation_source', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calculation_id')->constrained('calculations')->cascadeOnDelete();
            $table->foreignId('source_id')->constrained('sources')->restrictOnDelete();
            $table->string('rule_code', 48)->nullable();    // amal-ummi, radd, gawalan, bagian-nisan
            $table->timestamps();

            $table->unique(['calculation_id', 'source_id', 'rule_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculation_source');
    }
};
