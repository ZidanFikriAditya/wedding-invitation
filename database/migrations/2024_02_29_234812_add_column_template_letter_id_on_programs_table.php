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
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignIdFor(TemplateLetter::class)->nullable()->constrained();
            $table->longText('current_template_letter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['template_letter_id']);
            $table->dropColumn('template_letter_id');
            $table->dropColumn('current_template_letter');
        });
    }
};
