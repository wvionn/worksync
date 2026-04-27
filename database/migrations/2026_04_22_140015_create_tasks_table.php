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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Di-assign ke siapa
        $table->string('title'); // Contoh: Buat API Login Mahasiswa
        $table->text('description')->nullable();
        $table->string('module_name')->nullable(); // Opsional: misal "Modul Keuangan", "Frontend"
        $table->enum('status', ['todo', 'doing', 'done', 'overdue'])->default('todo');
        $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
        $table->date('due_date')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
