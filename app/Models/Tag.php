<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function post_tags(){
        return $this->hasMany('App\Models\PostTags', 'id', 'tag_id')->withTrashed();
    }
}
