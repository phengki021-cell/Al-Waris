<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('civil_wills', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 64)->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('has_will')->default(false);
            $table->date('will_date')->nullable();
            $table->string('will_form', 32)->nullable(); // umum|olografis|tertutup
            $table->string('will_document', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('civil_will_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('civil_will_id')->constrained('civil_wills')->cascadeOnDelete();
            $table->string('name', 120)->nullable();
            $table->string('relation', 64)->nullable();
            $table->string('gift_type', 32)->nullable(); // uang|barang|hak
            $table->string('object', 255)->nullable();
            $table->decimal('value', 20, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('civil_bequests', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 64)->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('donor', 120)->nullable();
            $table->string('recipient', 120)->nullable();
            $table->string('relation', 64)->nullable();
            $table->date('given_at')->nullable();
            $table->string('object', 255)->nullable();
            $table->string('asset_type', 32)->nullable();
            $table->decimal('value', 20, 2)->nullable();
            $table->string('document', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('civil_bequests');
        Schema::dropIfExists('civil_will_beneficiaries');
        Schema::dropIfExists('civil_wills');
    }
};