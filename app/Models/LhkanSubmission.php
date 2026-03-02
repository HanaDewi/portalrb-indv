<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LhkanSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'periode_id',
        'status',
        'jml_aparatur',
        'jml_wajib_lhkpn',
        'jml_non_wajib_lhkpn',
        'realisasi_lhkpn',
        'realisasi_spt_non_lhkpn',
        'belum_spt_non_lhkpn',
        'total_belum_lhkan',
        'link_rekap_gdrive',
        'catatan',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'jml_aparatur' => 'integer',
        'jml_wajib_lhkpn' => 'integer',
        'jml_non_wajib_lhkpn' => 'integer',
        'realisasi_lhkpn' => 'integer',
        'realisasi_spt_non_lhkpn' => 'integer',
        'belum_spt_non_lhkpn' => 'integer',
        'total_belum_lhkan' => 'integer',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Boot method to calculate total_belum_lhkan automatically
     */
    protected static function booted()
    {
        static::saving(function ($submission) {
            $submission->total_belum_lhkan =
                ($submission->jml_wajib_lhkpn - $submission->realisasi_lhkpn)
                + $submission->belum_spt_non_lhkpn;
        });
    }

    /**
     * Get the instansi that owns the submission.
     */
    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id');
    }

    /**
     * Get the period that the submission belongs to.
     */
    public function period()
    {
        return $this->belongsTo(LhkanPeriod::class, 'periode_id');
    }

    /**
     * Get the pics for the submission.
     */
    public function pics()
    {
        return $this->hasManyThrough(
            LhkanPic::class,
            KlpdInstansi::class,
            'id',
            'instansi_id',
            'instansi_id',
            'id'
        );
    }

    /**
     * Get the change requests for the submission.
     */
    public function changeRequests()
    {
        return $this->hasMany(LhkanChangeRequest::class, 'submission_id');
    }

    /**
     * Get the logs for the submission.
     */
    public function logs()
    {
        return $this->hasMany(LhkanLog::class, 'submission_id');
    }

    /**
     * Scope to only include submitted submissions.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope to only include approved submissions.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to only include draft submissions.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Check if submission is submitted.
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if submission is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if submission is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if submission is edit requested.
     */
    public function isEditRequested(): bool
    {
        return $this->status === 'edit_requested';
    }

    /**
     * Check if submission is edit approved.
     */
    public function isEditApproved(): bool
    {
        return $this->status === 'edit_approved';
    }

    /**
     * Submit the submission.
     */
    public function submit(): void
    {
        $this->status = 'submitted';
        $this->submitted_at = now();
        $this->save();
    }

    /**
     * Approve the submission.
     */
    public function approve(): void
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Reject the submission.
     */
    public function reject(): void
    {
        $this->status = 'rejected';
        $this->save();
    }

    /**
     * Request edit for the submission.
     */
    public function requestEdit(): void
    {
        $this->status = 'edit_requested';
        $this->save();
    }

    /**
     * Approve edit request for the submission.
     */
    public function approveEdit(): void
    {
        $this->status = 'edit_approved';
        $this->save();
    }

    /**
     * Check if the submission can be edited.
     */
    public function canBeEdited(): bool
    {
        return ($this->isDraft() || $this->isEditApproved()) && !$this->period->isLocked();
    }

    /**
     * Calculate completion percentage based on LHKPN and SPT reporting.
     */
    public function getCompletionPercentage(): float
    {
        if ($this->jml_aparatur === 0) {
            return 0;
        }

        $totalReported = $this->realisasi_lhkpn + $this->realisasi_spt_non_lhkpn;
        return ($totalReported / $this->jml_aparatur) * 100;
    }

    /**
     * Get formatted completion percentage.
     */
    public function getFormattedCompletion(): string
    {
        return number_format($this->getCompletionPercentage(), 2) . '%';
    }
}