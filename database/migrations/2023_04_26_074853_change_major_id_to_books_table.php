<?php

use App\Models\Major;
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
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeignIdFor(Major::class);
            $table->dropColumn('major_id');
        });
        Schema::table('books', function (Blueprint $table) {
            $table->string('major_id')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignIdFor(Major::class)->nullable()->after('price')->constrained()->cascadeOnDelete();
        });
    }
};
