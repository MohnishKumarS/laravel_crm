<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

/**
 * Read-only view into the marketplace catalog (marketplace_new.sma_products)
 * for the affiliate portal's product-browsing screen. Adjust $fillable/
 * casts once you confirm exact sma_products column names - only id,
 * name, price, image are assumed here based on the CI code seen earlier
 * (product_details->name, ->price, etc.).
 */
class Product extends Model
{
    protected $connection = 'marketplace'; // same connection key used for Order
    protected $table = 'products'; // resolves to sma_products via connection prefix

    public $timestamps = false;

      public function productVariant()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
}
