<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            if (! Schema::hasColumn('ratings', 'organizer_id')) {
                $table->foreignId('organizer_id')->nullable()->after('event_id')->constrained('users')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('ratings', 'review')) {
                $table->text('review')->nullable()->after('score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            if (Schema::hasColumn('ratings', 'organizer_id')) {
                $table->dropForeign(['organizer_id']);
                $table->dropColumn('organizer_id');
            }

            if (Schema::hasColumn('ratings', 'review')) {
                $table->dropColumn('review');
            }
        });
    }
};
