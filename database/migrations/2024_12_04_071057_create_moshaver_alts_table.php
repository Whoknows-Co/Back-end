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
        Schema::create('moshaver_alts', function (Blueprint $table) {
            $table->id();
            $table->string('moshaver_first_name');
            $table->string('moshaver_last_name');
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->text('more_description')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('degree')->nullable();
            $table->string('subject');
            $table->time('first_slot');
            $table->json('best_students')->nullable(); // JSON field
            $table->json('contact')->nullable(); // JSON field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moshaver_alts');
    }
};
