<?php

use App\Models\Procurement;
use App\Models\Major;
use App\Models\Publisher;
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
    Schema::create('books', function (Blueprint $table) {
      $table->id();
      $table->string('title')->nullable();
      $table->string('isbn')->nullable();
      $table->string('author_name')->nullable();
      $table->year('published_year')->nullable();
      $table->string('price')->nullable();
      $table->foreignIdFor(Major::class)->nullable()->constrained();
      $table->string('source')->nullable();
      $table->boolean('is_chosen')->nullable();
      $table->boolean('is_verified')->nullable();
      $table->foreignIdFor(Procurement::class)->nullable()->constrained();
      $table->string('eksemplar')->nullable();
      $table->string('suplemen')->nullable();
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
    Schema::dropIfExists('books');
  }
};
