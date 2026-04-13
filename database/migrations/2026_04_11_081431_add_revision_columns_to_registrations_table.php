<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Revision flow columns
            $table->string('revision_token', 64)->nullable()->unique()->after('rejection_reason');
            $table->timestamp('revision_token_expires_at')->nullable()->after('revision_token');
            $table->text('revision_notes')->nullable()->after('revision_token_expires_at');
            $table->unsignedBigInteger('revision_requested_by')->nullable()->after('revision_notes');
            $table->timestamp('revision_requested_at')->nullable()->after('revision_requested_by');
            $table->timestamp('revision_submitted_at')->nullable()->after('revision_requested_at');

            // Track revision count to prevent spam
            $table->unsignedTinyInteger('revision_count')->default(0)->after('revision_submitted_at');

            $table->foreign('revision_requested_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['revision_requested_by']);
            $table->dropColumn([
                'revision_token', 'revision_token_expires_at', 'revision_notes',
                'revision_requested_by', 'revision_requested_at',
                'revision_submitted_at', 'revision_count',
            ]);
        });
    }
};