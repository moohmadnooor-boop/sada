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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 120)->unique();
            $table->string('slug', 120)->unique();
            $table->string('author', 80);
            $table->string('description', 500);
            $table->string('era', 40)->nullable();
            $table->string('cover_color', 7);
            $table->timestamps();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('book_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('book_id');
        });

        Schema::dropIfExists('books');
    }
};
