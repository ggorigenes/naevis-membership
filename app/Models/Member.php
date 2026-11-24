<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'membership_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'latitude',
        'longitude',
        'status',
        'membership_category_id',
        'membership_level_id',
    ];

    public function category()
    {
        return $this->belongsTo(MembershipCategory::class, 'membership_category_id');
    }

    public function level()
    {
        return $this->belongsTo(MembershipLevel::class, 'membership_level_id');
    }

    public function transactions()
    {
        return $this->hasMany(MembershipTransaction::class);
    }
}