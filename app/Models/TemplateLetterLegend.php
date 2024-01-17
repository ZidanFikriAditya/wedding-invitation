<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateLetterLegend extends Model
{
    use HasFactory;

    protected $fillable = [
        'legend',
        'description',
        'template_letter_id'
    ];

    public function templateLetter()
    {
        return $this->belongsTo(TemplateLetter::class);
    }
}
