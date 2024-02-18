<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadTemplateLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_letter_id',
        'path_template',
    ];

    public function templateLetter()
    {
        return $this->belongsTo(TemplateLetter::class, 'template_letter_id');
    }
}
