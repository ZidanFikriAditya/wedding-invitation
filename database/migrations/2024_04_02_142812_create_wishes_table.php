<?php

use App\Models\LetterInvitation;
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
        Schema::create('wishes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LetterInvitation::class)->constrained()->cascadeOnDelete();
            $table->string('wishes');
            $table->string('other_people')->nullable();
            $table->enum('confirmation', ['datang', 'tidak datang'])->default('datang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishes');
    }
};
