<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LhkanLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'user_id',
        'action',
        'description',
        'ip_address',
    ];

    /**
     * Get the submission that owns the log.
     */
    public function submission()
    {
        return $this->belongsTo(LhkanSubmission::class, 'submission_id');
    }

    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get display name for the action.
     */
    public function getActionDisplayName(): string
    {
        $actionNames = [
            'created' => 'Dibuat',
            'submitted' => 'Disubmit',
            'approved' => 'Diapprove',
            'rejected' => 'Ditolak',
            'updated' => 'Diperbarui',
        ];

        return $actionNames[$this->action] ?? $this->action;
    }

    /**
     * Get action badge color.
     */
    public function getActionBadgeColor(): string
    {
        $colors = [
            'created' => 'primary',
            'submitted' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'updated' => 'warning',
        ];

        return $colors[$this->action] ?? 'secondary';
    }
}