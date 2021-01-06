<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrizesCategories extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','image','fee'];
    public $with = ['PrizesSubCategories'];


    public function PrizesSubCategories() {
        return $this->hasMany('App\Models\PrizesSubCategories');
    }
}
