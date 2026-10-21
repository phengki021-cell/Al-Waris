<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('heir_id')->constrained('heirs')->cascadeOnDelete();
            $table->unsignedSmallInteger('numerator');
            $table->unsignedSmallInteger('denominator');
            $table->string('condition', 500);              // deskripsi syarat bagian
            $table->string('condition_hash', 64);          // unik-matching untuk constraint
            $table->text('note')->nullable();
            $table->foreignId('source_id')->nullable()->constrained('sources')->nullOnDelete();
            $table->timestamps();

            $table->unique(['heir_id', 'numerator', 'denominator', 'condition_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shares');
    }
};
