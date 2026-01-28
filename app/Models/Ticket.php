<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'subject',
        'text',
        'status',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'answered_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function scopeStatus(Builder $query, TicketStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeCreatedBetween(Builder $query, $from, $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeByCustomerEmail(Builder $query, string $email): Builder
    {
        return $query->whereHas('customer', fn($q) => $q->where('email', $email));
    }

    public function scopeByCustomerPhone(Builder $query, string $phone): Builder
    {
        return $query->whereHas('customer', fn($q) => $q->where('phone', $phone));
    }
}
