<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * CATATAN: migration ini idempotent (aman dijalankan berkali-kali / di DB
     * yang tabel `organizations`-nya mungkin sudah dibuat manual sebelumnya
     * lewat phpMyAdmin). Kolom yang belum ada akan ditambal, bukan ditimpa.
     */
    public function up(): void
    {
        if (! Schema::hasTable('organizations')) {
            Schema::create('organizations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('logo_path')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('contact_phone')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('bank_account_number')->nullable();
                $table->string('bank_account_name')->nullable();
                // pending  = baru daftar, menunggu verifikasi superadmin
                // approved = boleh membuat & menjual tiket event
                // suspended= dibekukan oleh superadmin
                $table->string('status')->default('pending');
                $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });

            return;
        }

        // Tabel sudah ada sebelumnya (dibuat manual) -> tambal kolom yang belum ada
        Schema::table('organizations', function (Blueprint $table) {
            if (! Schema::hasColumn('organizations', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (! Schema::hasColumn('organizations', 'description')) {
                $table->text('description')->nullable();
            }
            if (! Schema::hasColumn('organizations', 'logo_path')) {
                $table->string('logo_path')->nullable();
            }
            if (! Schema::hasColumn('organizations', 'contact_email')) {
                $table->string('contact_email')->nullable();
            }
            if (! Schema::hasColumn('organizations', 'contact_phone')) {
                $table->string('contact_phone')->nullable();
            }
            if (! Schema::hasColumn('organizations', 'bank_name')) {
                $table->string('bank_name')->nullable();
            }
            if (! Schema::hasColumn('organizations', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable();
            }
            if (! Schema::hasColumn('organizations', 'bank_account_name')) {
                $table->string('bank_account_name')->nullable();
            }
            if (! Schema::hasColumn('organizations', 'status')) {
                $table->string('status')->default('pending');
            }
            if (! Schema::hasColumn('organizations', 'owner_id')) {
                $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
