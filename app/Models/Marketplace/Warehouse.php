<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $connection = 'marketplace';

    protected $table = 'warehouses';
}
