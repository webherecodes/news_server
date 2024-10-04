<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsUtilities extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'likes',
        'dislikes'
    ];

    protected $casts = [
        'likes' => 'array',
        'dislikes' => 'array',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
