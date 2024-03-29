<?php

use App\Models\Major;
use App\Models\Procurement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_books', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->text('author_name')->nullable();
            $table->year('published_year')->nullable();
            $table->string('price', 9)->nullable();
            $table->foreignIdFor(Major::class)->nullable()->constrained()->cascadeOnDelete();
            $table->text('summary')->nullable();
            $table->text("cover")->nullable();
            $table->string('source', 191)->nullable();
            $table->boolean('is_chosen')->nullable();
            $table->boolean('is_verified')->nullable();
            $table->foreignIdFor(Procurement::class)->nullable()->constrained()->cascadeOnDelete();
            $table->integer('eksemplar')->nullable();
            $table->string('suplemen', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procurement_books');
    }
};
