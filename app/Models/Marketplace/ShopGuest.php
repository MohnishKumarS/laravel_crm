<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class ShopGuest extends Model
{
    protected $connection = 'marketplace';

    protected $table = 'guest_customers';
}
