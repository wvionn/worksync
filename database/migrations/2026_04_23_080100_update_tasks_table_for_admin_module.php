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
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable()->after('due_date');

            $table->index(['status', 'priority']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['assigned_to']);
            $table->dropIndex(['status', 'priority']);
            $table->dropIndex(['due_date']);
            $table->dropColumn([
                'project_id',
                'assigned_to',
                'completed_at',
            ]);
        });
    }
};
