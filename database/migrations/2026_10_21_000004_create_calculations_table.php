<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_token', 64)->nullable()->index();
            $table->string('reference_code', 20)->unique();     // contoh: ALW-2026-000123
            $table->decimal('estate_total', 20, 2)->default(0); // total harta
            $table->decimal('debts_total', 20, 2)->default(0);
            $table->decimal('bequests_total', 20, 2)->default(0); // wasiat (<= 1/3)
            $table->decimal('funeral_expense', 20, 2)->default(0);
            $table->decimal('net_estate', 20, 2)->default(0);
            $table->string('method', 24)->nullable();           // amal_ummi|aul|radd|gawalan|...
            $table->string('status', 24)->default('draft');     // draft|valid|needs_attention|invalid
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculations');
    }
};
