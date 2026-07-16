<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $guarded = [];

    public function visitor(){
        return $this->belongsTo(Visitor::class);
    }
}
