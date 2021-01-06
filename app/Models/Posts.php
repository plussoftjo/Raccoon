<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Posts extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','body','image'];

    public $with = ['Likes','Comments'];

    public function User() {
        return $this->belongsTo('App\Models\User');
    }

    public function Likes() {
        return $this->hasMany('App\Models\Likes');
    }

    public function Comments() {
        return $this->hasMany('App\Models\Comments');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Amman')
            ->toDateTimeString()
        ;
    }
}
