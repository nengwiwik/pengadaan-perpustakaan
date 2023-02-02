<?php

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
        Schema::table('invoices', function (Blueprint $table) {
            $table->after('cancelled_date', function ($table) {
                $table->bigInteger('total_books')->default(0);
                $table->bigInteger('total_items')->default(0);
                $table->bigInteger('total_price')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('total_books');
            $table->dropColumn('total_items');
            $table->dropColumn('total_price');
        });
    }
};
