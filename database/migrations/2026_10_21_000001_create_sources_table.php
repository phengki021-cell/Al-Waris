<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('title', 200);
            $table->string('type', 24)->default('kitab'); // quran|hadis|kitab|kompilasi|fatwa|lainnya
            $table->string('reference', 200)->nullable();
            $table->string('url', 500)->nullable();
            $table->text('excerpt')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
