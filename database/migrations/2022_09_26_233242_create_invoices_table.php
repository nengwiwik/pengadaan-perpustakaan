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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->foreignIdFor(Publisher::class)->nullable()->constrained();
            $table->foreignIdFor(Campus::class)->nullable()->constrained();
            $table->text('publisher_note')->nullable();
            $table->text('campus_note')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('verified_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->date('cancelled_date')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
