<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'marketplace';

    protected $table = 'products';
}
