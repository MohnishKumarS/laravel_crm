<?php

namespace App\Models\Tool;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
        protected $fillable = [
        'template_id',
        'name',
        'recipient_type',
        'status',
        'total_recipients',
        'queued_count',
        'sent_count',
        'failed_count',
        'scheduled_at',
        'started_at',
        'completed_at',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class,'template_id');
    }

    public function recipients()
    {
        return $this->hasMany(EmailCampaignRecipient::class,'campaign_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
