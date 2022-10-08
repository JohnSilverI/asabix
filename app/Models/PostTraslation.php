<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTraslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'language_id',
        'title',
        'description',
        'content'
    ];

    public function post()
    : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function language()
    : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
