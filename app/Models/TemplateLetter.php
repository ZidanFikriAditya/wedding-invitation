<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TemplateLetter extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'owned_id',
        'is_public',
    ];

    public $appends = ['body'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($templateLetter) {
            try {
                Storage::deleteDirectory($templateLetter->path_template);
            } catch (\Exception $e) {
                // 
            }
            $templateLetter->legends()->delete();
        });
    }

    public function owned()
    {
        return $this->belongsTo(User::class, 'owned_id');
    }

    public function legends()
    {
        return $this->hasMany(TemplateLetterLegend::class, 'template_letter_id');
    }

    public function uploadTemplateLetter()
    {
        return $this->hasOne(UploadTemplateLetter::class, 'template_letter_id');
    }

    public function getBodyAttribute($value)
    {
        return str_replace("{storage}", url('storage/template-undangan/'), $value);
    }
}
