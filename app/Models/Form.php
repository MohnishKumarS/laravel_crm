<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    //
protected $fillable = [
    'title', 'slug', 'fields', 'active',
    'send_email', 'email_field_name',
    'customer_subject', 'customer_template',
    'admin_email', 'admin_subject', 'admin_template',
];

protected $casts = [
    'fields'     => 'array',
    'active'     => 'boolean',
    'send_email' => 'boolean',
];

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class)->latest();
    }
}
