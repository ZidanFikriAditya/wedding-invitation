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
        Schema::create('template_letter_legends', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\TemplateLetter::class, 'template_letter_id')->constrained('template_letters')->cascadeOnDelete();
            $table->string('legend');
            $table->string('description');
            $table->string('type')->default('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_letter_legends');
    }
};
