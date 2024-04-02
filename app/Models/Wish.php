<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_invitation_id',
        'wishes',
        'other_people',
        'confirmation',
    ];

    public function letterInvitation()
    {
        return $this->belongsTo(LetterInvitation::class);
    }
}
