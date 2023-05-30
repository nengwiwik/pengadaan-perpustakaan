<?php

use App\Models\Campus;
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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable();
            $table->foreignIdFor(Publisher::class)->nullable()->constrained();
            $table->foreignIdFor(Campus::class)->nullable()->constrained();
            $table->string('status', 10)->nullable();
            $table->string('invoice', 50)->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('approved_at')->nullable();
            $table->date('verified_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->date('cancelled_date')->nullable();
            $table->bigInteger('total_books')->default(0);
            $table->bigInteger('total_items')->default(0);
            $table->bigInteger('total_price')->default(0);
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
        Schema::dropIfExists('procurements');
    }
};
