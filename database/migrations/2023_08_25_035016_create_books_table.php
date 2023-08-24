<?php

use App\Models\Campus;
use App\Models\Procurement;
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
            $table->integer('eksemplar')->default(1);
            $table->string('title', 100)->nullable();
            $table->string('isbn', 20)->nullable();
            $table->string('author_name', 100)->nullable();
            $table->year('published_year')->nullable();
            $table->string('price', 9)->nullable();
            $table->string('suplemen', 20)->nullable();
            $table->foreignIdFor(Procurement::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Campus::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
