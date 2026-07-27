<?php

namespace App\Models\Tool;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
       protected $fillable = [
        'name',
        'subject',
        'body',
        'status',
        'category',
        'description',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function campaigns()
    // {
    //     return $this->hasMany(EmailCampaign::class);
    // }
}
