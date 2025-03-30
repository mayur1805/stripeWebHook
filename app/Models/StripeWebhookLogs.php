<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeWebhookLogs extends Model
{
    protected $fillable = ['payment_intent_id', 'type', 'payload', 'amount', 'currency', 'status'];
}
