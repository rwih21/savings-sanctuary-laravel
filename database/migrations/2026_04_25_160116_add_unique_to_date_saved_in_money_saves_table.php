<?php

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
        Schema::table('money_saves', function (Blueprint $table) {
            $table->unique('date_saved');
        });
    }

    public function down(): void
    {
        Schema::table('money_saves', function (Blueprint $table) {
            $table->dropUnique(['date_saved']);
        });
    }
};
