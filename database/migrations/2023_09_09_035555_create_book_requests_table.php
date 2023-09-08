<?php

use App\Models\BookRequest;
use App\Models\User;
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
        Schema::create('book_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->text('title')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->text('author_name')->nullable();
            $table->year('published_year')->nullable();
            $table->text('publisher_name')->nullable();
            $table->string('price', 9)->nullable();
            $table->text('dosen_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('status', 20)->default(BookRequest::STATUS_REQUESTED);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_requests');
    }
};
