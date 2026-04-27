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
        Schema::table('activities', function (Blueprint $table) {
            $table->string('title')->default('System activity')->after('user_id');
            $table->text('description')->nullable()->after('title');
            $table->string('category')->default('system')->after('description');
            $table->boolean('is_read')->default(false)->after('category');
            $table->string('link')->nullable()->after('is_read');
            $table->timestamp('occurred_at')->nullable()->after('link');

            $table->index(['category', 'is_read']);
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex(['category', 'is_read']);
            $table->dropIndex(['occurred_at']);
            $table->dropColumn([
                'title',
                'description',
                'category',
                'is_read',
                'link',
                'occurred_at',
            ]);
        });
    }
};
