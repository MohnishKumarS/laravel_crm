<?php

namespace App\Models\Tool;

use Illuminate\Database\Eloquent\Model;

class EmailCampaignRecipient extends Model
{
    protected $fillable = [
        'campaign_id',
        'recipient_type',
        'recipient_id',
        'name',
        'email',
        'sender_email',
        'status',
        'error_message',
        'sent_at',
        'attempts',
        'open_count',
        'opened_at',
        'click_count',
        'clicked_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }
}
