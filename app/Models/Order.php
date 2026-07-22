<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'marketplace';
    protected $table = 'sales'; // resolves to sma_sales via the connection's 'prefix' => 'sma_'

    // Add $fillable / $casts once you confirm real column names from sma_sales
}