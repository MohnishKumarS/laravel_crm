<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class VisitorViews extends Model
{
    protected $connection = 'marketplace';

    protected $table = 'visitor_views';

    public function visitor()
    {
        return $this->belongsTo(VisitorLogs::class, 'visitor_id', 'id');
    }
}
