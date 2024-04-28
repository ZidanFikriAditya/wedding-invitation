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
        Schema::create('letter_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number')->unique();
            $table->foreignIdFor(\App\Models\Program::class, 'program_id')->constrained('programs')->cascadeOnDelete();

            $table->string('receiver_name');
            $table->string('receiver_number');

            $table->date('sent_at')->nullable();
            $table->enum('status', ['pending', 'sending', 'sended'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_invitations');
    }
};
