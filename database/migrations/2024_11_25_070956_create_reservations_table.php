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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daneshamooz_id')->constrained('daneshamooz')->onDelete('cascade');
            $table->foreignId('moshaver_id')->constrained('moshaver')->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->enum('status',['pending','approved','cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
