<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owned_id',
        'phone_number',
        'address',
        'social_media',
        'template_letter_id',
        'others',
        'current_template_letter'
    ];

    protected $casts = [
        'social_media' => 'array',
        'others' => 'array',
    ];

    public function owned()
    {
        return $this->belongsTo(User::class, 'owned_id');
    }

    public function templateLetter()
    {
        return $this->belongsTo(TemplateLetter::class);
    }

    public function getCurrentTemplateLetterAttribute($value)
    {
        return str_replace("{storage}", url('storage/template-undangan/'), $value);
    }
}
