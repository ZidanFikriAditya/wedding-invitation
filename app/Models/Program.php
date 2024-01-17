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
        'social_media'
    ];

    protected $casts = [
        'social_media' => 'array'
    ];

    public function owned()
    {
        return $this->belongsTo(User::class, 'owned_id');
    }
}
