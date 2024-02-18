<?php

use App\Models\TemplateLetter;
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
        Schema::create('upload_template_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TemplateLetter::class, 'template_letter_id')->constrained('template_letters')->cascadeOnDelete();
            $table->text('path_template')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_template_letters');
    }
};
