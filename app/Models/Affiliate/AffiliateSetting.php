<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Model;

class AffiliateSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;
}
