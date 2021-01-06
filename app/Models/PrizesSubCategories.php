<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrizesSubCategories extends Model
{
    use HasFactory;

    protected $fillable = ['prizes_categories_id','title','description','image'];

    public function PrizesCategories() {
        return $this->belongsTo('App\Models\PrizesCategories');
    }
}
