<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    protected $connection = 'marketplace';

    protected $table = 'users';
}
