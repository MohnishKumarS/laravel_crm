<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class VisitorLogs extends Model
{
    protected $connection = 'marketplace';

    protected $table = 'visitor_logs';

    public function pageViews()
    {
        return $this->hasMany(VisitorViews::class, 'visitor_id', 'id');
    }
}
