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
            Schema::table('registrations', function (Blueprint $table) {
                // Kita paksa ubah menjadi string biasa agar tidak dibatasi oleh ENUM lama
                $table->string('approval_status')->default('pending_review')->change();
            });
        }

        public function down(): void
        {
            // Tidak perlu diisi jika hanya untuk fix
        }
};
