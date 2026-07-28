<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicPage extends Model
{
    use SoftDeletes;

      protected $fillable = [
        'title',
        'subtitle',
        'heading',
        'description',
        'page_url',
        'products_id',
        'banner_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
