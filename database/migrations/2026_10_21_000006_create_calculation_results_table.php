<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calculation_id')->constrained('calculations')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('calculation_participants')->cascadeOnDelete();
            $table->unsignedBigInteger('numerator');   // pecahan eksak n/d — bukan float
            $table->unsignedBigInteger('denominator');
            $table->string('share_text', 24);          // "1/8" untuk tampilan saja
            $table->decimal('amount', 20, 2)->default(0);
            $table->string('rounding_method', 16)->nullable(); // floor|ceil|none
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('calculation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculation_results');
    }
};
