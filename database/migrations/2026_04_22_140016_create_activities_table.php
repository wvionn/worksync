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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Activity title
            $table->text('description')->nullable(); // Activity description
            $table->string('category')->default('general'); // Category: task, project, user, etc
            $table->boolean('is_read')->default(false); // Read status
            $table->string('link')->nullable(); // Link to related resource
            $table->timestamp('occurred_at')->useCurrent(); // When activity occurred
            $table->timestamps();
            
            $table->index(['user_id', 'is_read']);
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
