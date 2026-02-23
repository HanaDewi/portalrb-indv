<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LhkanPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun',
        'nama',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    /**
     * Get the submissions for the period.
     */
    public function submissions()
    {
        return $this->hasMany(LhkanSubmission::class);
    }

    /**
     * Scope to only include open periods.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope to only include locked periods.
     */
    public function scopeLocked($query)
    {
        return $query->where('status', 'locked');
    }

    /**
     * Check if period is locked.
     */
    public function isLocked(): bool
    {
        return $this->status === 'locked';
    }

    /**
     * Check if period is open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Lock the period.
     */
    public function lock(): void
    {
        $this->status = 'locked';
        $this->save();
    }

    /**
     * Unlock the period.
     */
    public function unlock(): void
    {
        $this->status = 'open';
        $this->save();
    }
}