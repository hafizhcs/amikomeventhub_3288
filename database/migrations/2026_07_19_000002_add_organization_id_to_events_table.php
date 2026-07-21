<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kolom ini nullable dengan sengaja: event lama (dibuat sebelum sistem
     * organisasi ada) tetap valid dengan organization_id = NULL, dan katalog
     * publik / checkout / pembayaran tidak menyentuh kolom ini sama sekali —
     * jadi menambah kolom ini tidak berpengaruh ke fitur yang sudah jalan.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'organization_id')) {
                $table->foreignId('organization_id')
                    ->nullable()
                    ->after('organizer_id')
                    ->constrained('organizations')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'organization_id')) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            }
        });
    }
};
