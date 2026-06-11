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
        Schema::table('group_messages', function (Blueprint $table) {
            // Check and add columns if they don't exist
            if (!Schema::hasColumn('group_messages', 'project_id')) {
                $table->foreignId('project_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('group_messages', 'sender_id')) {
                $table->foreignId('sender_id')->after('project_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('group_messages', 'message')) {
                $table->text('message')->after('sender_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_messages', function (Blueprint $table) {
            if (Schema::hasColumn('group_messages', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
            if (Schema::hasColumn('group_messages', 'sender_id')) {
                $table->dropForeign(['sender_id']);
                $table->dropColumn('sender_id');
            }
            if (Schema::hasColumn('group_messages', 'message')) {
                $table->dropColumn('message');
            }
        });
    }
};
