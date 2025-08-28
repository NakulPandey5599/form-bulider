<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
       Schema::create('form_submissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
        $table->json('data');    // the user answers
        $table->json('meta')->nullable(); // ip, ua, referrer
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
