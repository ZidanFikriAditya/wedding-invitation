<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LetterInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_number',
        'subject',
        'program_id',
        'receiver_name',
        'sent_at',
        'status'
    ];

    protected $casts = [
        'legends' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = LetterInvitation::latest()->first()?->letter_number;
            Log::info([
                'number' => substr($number, 10),
                'number_int' => (int) substr($number, 10),
            ]);
            $model->letter_number = 'LI_' . date('mY') . '_' . str_pad(($number ? (int) substr($number, 10) : 0) + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function templateLetter()
    {
        return $this->belongsTo(TemplateLetter::class);
    }

    public function getLegendsAttribute($value)
    {
        return collect(json_decode($value))->map(function ($item) {
            return (object) $item;
        });
    }
}
