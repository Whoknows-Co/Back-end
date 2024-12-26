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
        Schema::create('reservation2', function (Blueprint $table) {
            $table->id();
            $table->string("student_first_name");
            $table->string("student_last_name");
            $table->string('level');
            $table->string('subject');
            $table->date('date_birth');
            $table->string("phone_number");
            $table->foreignId('moshaver_id')->constrained('moshaver')->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->string('status')->default('confirmed');
            $table->timestamps();
            $table->unique(['moshaver_id','date','time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation2');
    }
};
