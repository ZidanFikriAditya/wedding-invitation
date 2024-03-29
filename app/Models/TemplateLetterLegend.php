<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateLetterLegend extends Model
{
    use HasFactory;

    public $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'legend',
        'description',
        'template_letter_id',
        'type'
    ];

    public function templateLetter()
    {
        return $this->belongsTo(TemplateLetter::class);
    }
}
