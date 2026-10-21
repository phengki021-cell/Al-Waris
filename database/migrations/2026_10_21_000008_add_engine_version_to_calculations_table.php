<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calculations', function (Blueprint $table) {
            // Identitas versi mesin hitung saat hasil dibuat (ketertelusuran riwayat).
            $table->string('engine_version', 16)->nullable()->after('method');
        });
    }

    public function down(): void
    {
        Schema::table('calculations', function (Blueprint $table) {
            $table->dropColumn('engine_version');
        });
    }
};
