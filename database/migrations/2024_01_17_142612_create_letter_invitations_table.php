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
            $table->string('subject');
            $table->foreignIdFor(\App\Models\Program::class, 'program_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\TemplateLetter::class, 'template_letter_id')->constrained('template_letters')->cascadeOnDelete();
            
            $table->string('receiver_email');
            $table->string('receiver_phone_number');
            $table->string('receiver_name');

            $table->json('legends')->nullable();

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
