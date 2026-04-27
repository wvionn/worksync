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
        $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade'); // Terkait task apa
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang ngelakuin
        $table->string('action'); // Contoh: "Memindahkan task ke status Doing"
        $table->timestamps();
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
