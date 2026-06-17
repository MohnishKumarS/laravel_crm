<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    //

    protected $fillable = ['title', 'slug', 'fields', 'active'];

    protected $casts = [
        'fields' => 'array',   // auto json_decode/encode
        'active' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
