<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipTransaction extends Model
{
    protected $fillable = [
        'reference_number',
        'member_id',
        'membership_level_id',
        'transaction_type',
        'amount',
        'status',
        'start_date',
        'end_date',
        'verification_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'verification_sent_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class)->withTrashed();
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(MembershipLevel::class, 'membership_level_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}