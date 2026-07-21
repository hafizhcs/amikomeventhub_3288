<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Default 'approved' secara sengaja: seluruh event yang sudah ada
     * (dan event yang dibuat superadmin lewat panel admin lama) langsung
     * berstatus approved, jadi katalog publik tidak berubah sama sekali.
     * Hanya event yang dibuat lewat dashboard Organizer baru yang di-set
     * 'pending' secara eksplisit oleh controller-nya.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'status')) {
                $table->string('status')->default('approved')->after('organization_id');
            }
            if (! Schema::hasColumn('events', 'rejection_reason')) {
                $table->string('rejection_reason')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('events', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
