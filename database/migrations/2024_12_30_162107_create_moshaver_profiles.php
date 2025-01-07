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
        Schema::create('moshaver_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moshaver_id')->constrained('moshaver')->onDelete('cascade');
            $table->string('specialty')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->text('about')->nullable();
            $table->text('more_description')->nullable();
            $table->text('services')->nullable();
            $table->json('social_media')->nullable();
            $table->string('display_phone')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moshaver_profiles');
    }
};
