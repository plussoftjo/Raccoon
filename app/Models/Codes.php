<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Codes extends Model
{
    use HasFactory;

    protected $fillable = ['prizes_sub_categories_id','code','active'];

    public function PrizesSubCategories() {
        return $this->belongsTo('App\Models\PrizesSubCategories');
    }
}
