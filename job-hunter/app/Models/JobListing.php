<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'company', 'description'];

    public function getDisplayViewsAttribute(): int
    {
        return $this->views + (int) Redis::get("job:views:{$this->id}");
    }
}
