<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','bio','address'];

    public function User(){
        return $this->belongsTo('App\Models\User');
    }
}
