<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Orders extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','prizes_categories_id','prizes_sub_categories_id','codes_id','fee','logs'];

    public $with = ['Codes','PrizesCategories','PrizesSubCategories'];
    
    public function User() {
        return $this->belongsTo('App\Models\User');
    }

    public function PrizesCategories() {
        return $this->belongsTo('App\Models\PrizesCategories');
    }

    public function PrizesSubCategories() {
        return $this->belongsTo('App\Models\PrizesSubCategories');
    }

    public function Codes() {
        return $this->belongsTo('App\Models\Codes');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Amman')
            ->toDateTimeString()
        ;
    }
}
