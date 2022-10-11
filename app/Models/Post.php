<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    public function post_tags(){
        return $this->hasMany('App\Models\PostTags', 'id', 'post_id')->withTrashed();
    }
}
