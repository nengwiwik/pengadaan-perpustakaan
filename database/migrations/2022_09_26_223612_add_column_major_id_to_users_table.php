<?php

use App\Models\Campus;
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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignIdFor(Publisher::class)->nullable()->after('email')->constrained();
            $table->foreignIdFor(Campus::class)->nullable()->after('publisher_id')->constrained();
            $table->foreignIdFor(Major::class)->nullable()->after('campus_id')->constrained();
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
