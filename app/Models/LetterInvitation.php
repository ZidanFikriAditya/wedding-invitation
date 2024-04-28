<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
