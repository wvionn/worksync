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
            $table->string('title')->default('Untitled Task')->after('project_id');
            $table->text('description')->nullable()->after('title');
            $table->string('status')->default('todo')->after('description');
            $table->string('priority')->default('medium')->after('status');
            $table->foreignId('assigned_to')->nullable()->after('priority')->constrained('users')->nullOnDelete();
            $table->date('due_date')->nullable()->after('assigned_to');
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
                'title',
                'description',
                'status',
                'priority',
                'assigned_to',
                'due_date',
                'completed_at',
            ]);
        });
    }
};
