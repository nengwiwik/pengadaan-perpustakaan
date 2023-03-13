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
            $table->dropColumn(['publisher_note', 'campus_note']);
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
            $table->after('campus_id', function ($table) {
                $table->text('publisher_note')->nullable();
                $table->text('campus_note')->nullable();
            });
        });
    }
};
