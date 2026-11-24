<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('civil_family_members', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 64)->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role', 24);          // pewaris, pasangan, anak, ayah, ibu, saudara, cucu
            $table->string('name', 120)->nullable();
            $table->char('gender', 1)->nullable(); // m|f
            $table->date('birth_date')->nullable();
            $table->string('status', 24)->default('hidup'); // hidup|meninggal|tidak_diketahui
            $table->date('death_date')->nullable();
            $table->string('category', 24)->nullable(); // kandung|seayah|seibu (saudara)
            $table->foreignId('parent_id')->nullable()->constrained('civil_family_members')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['session_token', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('civil_family_members');
    }
};