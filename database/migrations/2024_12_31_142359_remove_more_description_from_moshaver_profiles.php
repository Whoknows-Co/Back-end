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
        Schema::table('moshaver_profiles', function (Blueprint $table) {
            $table->dropColumn('more_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moshaver_profiles', function (Blueprint $table) {
            $table->text('more_description')->nullable();
        });
    }
};
