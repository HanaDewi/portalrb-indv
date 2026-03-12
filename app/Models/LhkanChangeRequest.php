<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LhkanChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'field_name',
        'old_value',
        'new_value',
        'reason',
        'status',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * Get the submission that owns the change request.
     */
    public function submission()
    {
        return $this->belongsTo(LhkanSubmission::class, 'submission_id');
    }

    /**
     * Get the user who processed the change request.
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope to only include pending change requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to only include approved change requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to only include rejected change requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if change request is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if change request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if change request is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve the change request.
     */
    public function approve(int $processedBy): void
    {
        $this->status = 'approved';
        $this->processed_at = now();
        $this->processed_by = $processedBy;
        $this->save();
    }

    /**
     * Reject the change request.
     */
    public function reject(int $processedBy): void
    {
        $this->status = 'rejected';
        $this->processed_at = now();
        $this->processed_by = $processedBy;
        $this->save();
    }

    /**
     * Get display name for the field.
     */
    public function getFieldDisplayName(): string
    {
        if ($this->field_name === 'all') {
            return 'Semua Field';
        }

        $fieldNames = [
            'jml_aparatur' => 'Jumlah Aparatur',
            'jml_wajib_lhkpn' => 'Jumlah Wajib LHKPN',
            'jml_non_wajib_lhkpn' => 'Jumlah Non Wajib LHKPN',
            'realisasi_lhkpn' => 'Realisasi LHKPN',
            'realisasi_spt_non_lhkpn' => 'Realisasi SPT Non LHKPN',
            'belum_spt_non_lhkpn' => 'Belum SPT Non LHKPN',
            'link_rekap_gdrive' => 'Link Rekap Google Drive',
            'catatan' => 'Catatan',
            'pics' => 'Data PIC',
        ];

        return $fieldNames[$this->field_name] ?? $this->field_name;
    }
}