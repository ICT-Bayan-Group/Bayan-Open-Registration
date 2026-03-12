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
        if (Schema::hasColumn('registrations', 'alamat')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->dropColumn('alamat');
            });
        }
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('kota');
        });
    }
};
