<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'fee', 'validity_period', 'benefits'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}