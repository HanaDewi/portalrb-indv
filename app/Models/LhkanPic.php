<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LhkanPic extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'nama',
        'nomor_hp',
        'status',
        'added_by',
    ];

    /**
     * Get the instansi that owns the PIC.
     */
    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id');
    }

    /**
     * Scope to only include approved PICs.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to only include pending PICs.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if PIC is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if PIC is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Approve the PIC.
     */
    public function approve(): void
    {
        $this->status = 'approved';
        $this->save();
    }

    /**
     * Reject the PIC.
     */
    public function reject(): void
    {
        $this->status = 'rejected';
        $this->save();
    }
}