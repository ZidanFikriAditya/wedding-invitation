<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'owned_id',
        'is_public'
    ];

    public function owned()
    {
        return $this->belongsTo(User::class, 'owned_id');
    }
}
