<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['category', 'author'];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query
                ->where('title', 'like', '%' . request('search'). '%')
                ->orWhere('body', 'like', '%' . request('search'). '%');
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // A post is written by a user. A Post belongs to a User
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
