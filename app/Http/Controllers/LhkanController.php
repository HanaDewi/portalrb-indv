<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLhkanRequest;
use App\Http\Requests\UpdateLhkanRequest;
use App\Models\KlpdInstansi;
use App\Models\LhkanChangeRequest;
use App\Models\LhkanLog;
use App\Models\LhkanPeriod;
use App\Models\LhkanPic;
use App\Models\LhkanSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class LhkanController extends Controller
{
    protected $user;
    protected $level;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->level = $this->user->level;
            return $next($request);
        });
    }

    // ==================== ADMIN & TPN FEATURES ====================

    /**
     * Dashboard monitoring for Admin and TPN
     */
    public function dashboard(Request $request)
    {
        if (!in_array($this->level, ['admin', 'tpn'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $periodeId = $request->periode_id ?? LhkanPeriod::latest()->first()->id ?? null;
        $instansiId = $request->instansi_id ?? null;
        $status = $request->status ?? null;

        $periodes = LhkanPeriod::orderBy('tahun', 'desc')->get();

        $query = LhkanSubmission::with(['instansi', 'period', 'pics']);

        if ($periodeId) {
            $query->where('periode_id', $periodeId);
        }

        if ($instansiId && $this->level === 'admin') {
            $query->where('instansi_id', $instansiId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $submissions = $query->orderBy('created_at', 'desc')->limit(1000)->get();

        // Statistics
        $totalInstansi = KlpdInstansi::count();
        $submittedCount = LhkanSubmission::where('periode_id', $periodeId)
            ->where('status', '!=', 'draft')
            ->count();
        $approvedCount = LhkanSubmission::where('periode_id', $periodeId)
            ->where('status', 'approved')
            ->count();

        $completionRate = $totalInstansi > 0 ? round(($submittedCount / $totalInstansi) * 100, 2) : 0;

        return view('lhkan.admin.dashboard', compact(
            'submissions',
            'periodes',
            'periodeId',
            'instansiId',
            'status',
            'totalInstansi',
            'submittedCount',
            'approvedCount',
            'completionRate'
        ));
    }

    /**
     * Export data to CSV for TPN
     */
    public function exportCsv(Request $request)
    {
        if (!in_array($this->level, ['admin', 'tpn'])) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $periodeId = $request->periode_id;
        if (!$periodeId) {
            return redirect()->back()->with('error', 'Periode wajib dipilih.');
        }

        $submissions = LhkanSubmission::where('periode_id', $periodeId)
            ->with(['instansi', 'period', 'pics'])
            ->get();

        $data = [];
        foreach ($submissions as $submission) {
            $pics = $submission->pics->map(function ($pic) {
                return $pic->nama . ' (' . $pic->nomor_hp . ')';
            })->implode(', ');

            $data[] = [
                'Nama Instansi' => $submission->instansi->name ?? '-',
                'Periode' => $submission->period->nama ?? '-',
                'Status' => $submission->status,
                'Total Aparatur' => $submission->jml_aparatur,
                'Wajib LHKPN' => $submission->jml_wajib_lhkpn,
                'Tidak Wajib LHKPN' => $submission->jml_non_wajib_lhkpn,
                'Realisasi LHKPN' => $submission->realisasi_lhkpn,
                'Realisasi SPT Non LHKPN' => $submission->realisasi_spt_non_lhkpn,
                'Belum SPT Non LHKPN' => $submission->belum_spt_non_lhkpn,
                'Total Belum LHKAN' => $submission->total_belum_lhkan,
                'PIC' => $pics,
                'Link Rekap' => $submission->link_rekap_gdrive ?? '-',
                'Catatan' => $submission->catatan ?? '-',
                'Tanggal Submit' => $submission->submitted_at ? $submission->submitted_at->format('Y-m-d H:i:s') : '-',
            ];
        }

        $fileName = 'lhkan_report_' . date('Y_m_d_His') . '.csv';

        return (new FastExcel(collect($data)))->download($fileName);
    }

    // ==================== PERIODE MANAGEMENT (ADMIN) ====================

    /**
     * Index periode
     */
    public function periodeIndex()
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $periodes = LhkanPeriod::orderBy('tahun', 'desc')->get();

        return view('lhkan.admin.periode', compact('periodes'));
    }

    /**
     * Store periode
     */
    public function periodeStore(Request $request)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $validated = $request->validate([
            'tahun' => 'required|integer|unique:lhkan_periodes,tahun',
            'nama' => 'required|string|max:255',
            'status' => 'required|in:open,locked',
            'deskripsi' => 'nullable|string',
        ]);

        LhkanPeriod::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Periode berhasil dibuat.'
        ]);
    }

    /**
     * Update periode
     */
    public function periodeUpdate(Request $request, $id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $validated = $request->validate([
            'tahun' => 'required|integer|unique:lhkan_periodes,tahun,' . $id,
            'nama' => 'required|string|max:255',
            'status' => 'required|in:open,locked',
            'deskripsi' => 'nullable|string',
        ]);

        $periode = LhkanPeriod::findOrFail($id);
        $periode->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Periode berhasil diperbarui.'
        ]);
    }

    /**
     * Toggle periode lock status
     */
    public function periodeToggleLock(Request $request, $id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $periode = LhkanPeriod::findOrFail($id);

        if ($periode->status === 'open') {
            $periode->lock();
            $message = 'Periode berhasil dikunci. Instansi tidak dapat mengedit data.';
        } else {
            $periode->unlock();
            $message = 'Periode berhasil dibuka. Instansi dapat mengedit data.';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete periode
     */
    public function periodeDelete($id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $periode = LhkanPeriod::findOrFail($id);

        // Check if there are submissions
        if ($periode->submissions()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus periode yang sudah memiliki data pelaporan.');
        }

        $periode->delete();

        return redirect()->back()->with('success', 'Periode berhasil dihapus.');
    }

    /**
     * Delete LHKAN submission (data LHKAN instansi) - Admin only
     */
    public function submissionDelete($id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $submission = LhkanSubmission::findOrFail($id);
        $submission->delete();

        return redirect()->back()->with('success', 'Data LHKAN instansi berhasil dihapus.');
    }

    // ==================== PIC MANAGEMENT ====================

    /**
     * Index PIC for Admin
     */
    public function picIndex(Request $request)
    {
        if (!in_array($this->level, ['admin'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $instansiId = $request->instansi_id ?? null;
        $status = $request->status ?? null;

        $query = LhkanPic::with('instansi');

        if ($instansiId) {
            $query->where('instansi_id', $instansiId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pics = $query->orderBy('created_at', 'desc')->limit(1000)->get();
        $pendingCount = LhkanPic::pending()->count();

        return view('lhkan.admin.pic', compact('pics', 'instansiId', 'status', 'pendingCount'));
    }

    /**
     * Store PIC (Admin)
     */
    public function picStore(Request $request)
    {
        if (!in_array($this->level, ['admin', 'kl', 'provinsi', 'kabupaten'])) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $validated = $request->validate([
            'instansi_id' => 'required|exists:klpd_instansi_new,id',
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
        ]);

        // Determine status and added_by based on user level
        $validated['status'] = $this->level === 'admin' ? 'approved' : 'pending';
        $validated['added_by'] = $this->level === 'admin' ? 'admin' : 'instansi';

        LhkanPic::create($validated);

        $message = $this->level === 'admin'
            ? 'PIC berhasil ditambahkan.'
            : 'PIC berhasil diajukan. Menunggu persetujuan admin.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update PIC
     */
    public function picUpdate(Request $request, $id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
        ]);

        $pic = LhkanPic::findOrFail($id);
        $pic->update($validated);

        return redirect()->back()->with('success', 'PIC berhasil diperbarui.');
    }

    /**
     * Delete PIC
     */
    public function picDelete($id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $pic = LhkanPic::findOrFail($id);
        $pic->delete();

        return redirect()->back()->with('success', 'PIC berhasil dihapus.');
    }

    /**
     * Approve PIC
     */
    public function picApprove($id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $pic = LhkanPic::findOrFail($id);
        $pic->approve();

        return redirect()->back()->with('success', 'PIC berhasil di-approve.');
    }

    /**
     * Reject PIC
     */
    public function picReject($id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $pic = LhkanPic::findOrFail($id);
        $pic->reject();

        return redirect()->back()->with('success', 'PIC berhasil di-reject.');
    }

    // ==================== CHANGE REQUESTS (ADMIN) ====================

    /**
     * Index change requests
     */
    public function changeRequestIndex(Request $request)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $status = $request->status ?? 'pending';

        $changeRequests = LhkanChangeRequest::with(['submission.instansi', 'submission.period', 'processor'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('lhkan.admin.change_requests', compact('changeRequests', 'status'));
    }

    /**
     * Approve change request
     */
    public function changeRequestApprove($id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        DB::beginTransaction();
        try {
            $changeRequest = LhkanChangeRequest::findOrFail($id);
            $submission = $changeRequest->submission;

            // Ubah status submission ke edit_approved (user bisa edit)
            $submission->approveEdit();

            // Update change request status
            $changeRequest->approve($this->user->id);

            // Log action
            LhkanLog::create([
                'submission_id' => $submission->id,
                'user_id' => $this->user->id,
                'action' => 'edit_approved',
                'description' => 'Pengajuan edit disetujui, user dapat mengedit data',
                'ip_address' => request()->ip(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan perubahan disetujui. User sekarang dapat mengedit data.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving change request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses perubahan.');
        }
    }

    /**
     * Reject change request
     */
    public function changeRequestReject($id)
    {
        if ($this->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        DB::beginTransaction();
        try {
            $changeRequest = LhkanChangeRequest::findOrFail($id);
            $submission = $changeRequest->submission;

            // Kembalikan status submission ke submitted
            $submission->status = 'submitted';
            $submission->save();

            // Update change request status
            $changeRequest->reject($this->user->id);

            // Log action
            LhkanLog::create([
                'submission_id' => $submission->id,
                'user_id' => $this->user->id,
                'action' => 'edit_rejected',
                'description' => 'Pengajuan edit ditolak',
                'ip_address' => request()->ip(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan perubahan ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting change request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses perubahan.');
        }
    }

    // ==================== INSTANSI FEATURES ====================

    /**
     * Form input LHKAN for Instansi
     */
    public function form(Request $request)
    {
        if (!in_array($this->level, ['kl', 'provinsi', 'kabupaten'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $instansiId = $this->user->user_rel->instansi_id;
        $periodeId = $request->periode_id ?? LhkanPeriod::open()->latest()->first()?->id;
        $currentPeriod = $periodeId ? LhkanPeriod::find($periodeId) : null;

        $periodes = LhkanPeriod::orderBy('tahun', 'desc')->get();
        $pics = LhkanPic::where('instansi_id', $instansiId)->approved()->get();

        // Load submission: by submission_id (edit from history) or by current period, always with pics for form
        if ($request->submission_id) {
            $submission = LhkanSubmission::where('instansi_id', $instansiId)
                ->where('id', $request->submission_id)
                ->with(['pics', 'period'])
                ->first();
        }
        if (!isset($submission) && $currentPeriod) {
            $submission = LhkanSubmission::where('instansi_id', $instansiId)
                ->where('periode_id', $currentPeriod->id)
                ->with('pics')
                ->first();
        }
        $submission = $submission ?? null;

        // Check if user has submitted in active period
        $hasSubmitted = $submission && in_array($submission->status, ['submitted', 'approved', 'edit_requested']);
        $canEdit = $submission && $submission->isEditApproved();
        $isEditRequested = $submission && $submission->isEditRequested();

        // Check if period is locked
        $isLocked = $submission ? $submission->period->isLocked() : false;

        return view('lhkan.instansi.form', compact(
            'submission',
            'periodes',
            'periodeId',
            'currentPeriod',
            'pics',
            'isLocked',
            'instansiId',
            'hasSubmitted',
            'canEdit',
            'isEditRequested'
        ));
    }

    /**
     * Store or Update LHKAN submission
     */
    public function store(StoreLhkanRequest $request)
    {
        if (!in_array($this->level, ['kl', 'provinsi', 'kabupaten'])) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $instansiId = $this->user->user_rel->instansi_id;
        $periode = LhkanPeriod::findOrFail($request->periode_id);

        // Check if period is locked
        if ($periode->isLocked()) {
            return redirect()->back()->with('error', 'Periode ini sudah dikunci. Anda tidak dapat mengedit data.');
        }

        DB::beginTransaction();
        try {
            // Find existing submission
            $existingSubmission = LhkanSubmission::where('instansi_id', $instansiId)
                ->where('periode_id', $request->periode_id)
                ->first();

            // Determine if this is an edit after approval
            $isEditAfterApproval = $existingSubmission && $existingSubmission->isEditApproved();

            // Find or create submission
            $submission = LhkanSubmission::updateOrCreate(
                [
                    'instansi_id' => $instansiId,
                    'periode_id' => $request->periode_id,
                ],
                [
                    'status' => $isEditAfterApproval ? 'edit_approved' : 'draft',
                    'jml_aparatur' => $request->jml_aparatur,
                    'jml_wajib_lhkpn' => $request->jml_wajib_lhkpn,
                    'jml_non_wajib_lhkpn' => $request->jml_non_wajib_lhkpn,
                    'realisasi_lhkpn' => $request->realisasi_lhkpn,
                    'realisasi_spt_non_lhkpn' => $request->realisasi_spt_non_lhkpn,
                    'belum_spt_non_lhkpn' => $request->belum_spt_non_lhkpn,
                    'link_rekap_gdrive' => $request->link_rekap_gdrive,
                    'catatan' => $request->catatan,
                ]
            );

            // Update or create PICs
            // Delete existing approved PICs for this instansi
            LhkanPic::where('instansi_id', $instansiId)->delete();

            // Create new PICs
            foreach ($request->pics as $picData) {
                LhkanPic::create([
                    'instansi_id' => $instansiId,
                    'nama' => $picData['nama'],
                    'nomor_hp' => $picData['nomor_hp'],
                    'status' => 'approved',
                    'added_by' => 'instansi',
                ]);
            }

            // Log action
            LhkanLog::create([
                'submission_id' => $submission->id,
                'user_id' => $this->user->id,
                'action' => $submission->wasRecentlyCreated ? 'created' : 'updated',
                'description' => 'Data LHKAN ' . ($submission->wasRecentlyCreated ? 'dibuat' : 'diperbarui'),
                'ip_address' => request()->ip(),
            ]);

            if ($request->action === 'submit') {
                // Allow submit if: draft, edit_approved, or not yet submitted
                if ($submission->isSubmitted() || $submission->isApproved()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Data sudah disubmit.');
                }
                $submission->submit();
                LhkanLog::create([
                    'submission_id' => $submission->id,
                    'user_id' => $this->user->id,
                    'action' => $isEditAfterApproval ? 'resubmitted' : 'submitted',
                    'description' => $isEditAfterApproval ? 'Data LHKAN disubmit ulang setelah edit' : 'Data LHKAN disubmit',
                    'ip_address' => request()->ip(),
                ]);
                DB::commit();
                return redirect()->route('lhkan.history')->with('success', 'Data LHKAN berhasil disubmit.');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data LHKAN berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing LHKAN submission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Submit LHKAN submission
     */
    public function submit(Request $request)
    {
        if (!in_array($this->level, ['kl', 'provinsi', 'kabupaten'])) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $validated = $request->validate([
            'submission_id' => 'required|exists:lhkan_submissions,id',
        ]);

        $submission = LhkanSubmission::findOrFail($validated['submission_id']);

        // Check if period is locked
        if ($submission->period->isLocked()) {
            return redirect()->back()->with('error', 'Periode ini sudah dikunci. Anda tidak dapat mengirim data.');
        }

        // Check if already submitted
        if ($submission->isSubmitted() || $submission->isApproved()) {
            return redirect()->back()->with('error', 'Data sudah disubmit.');
        }

        DB::beginTransaction();
        try {
            $submission->submit();

            // Log action
            LhkanLog::create([
                'submission_id' => $submission->id,
                'user_id' => $this->user->id,
                'action' => 'submitted',
                'description' => 'Data LHKAN disubmit',
                'ip_address' => request()->ip(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data LHKAN berhasil disubmit.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting LHKAN: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mensubmit data.');
        }
    }

    /**
     * View submission history for Instansi
     */
    public function history(Request $request)
    {
        if (!in_array($this->level, ['kl', 'provinsi', 'kabupaten'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $instansiId = $this->user->user_rel->instansi_id;
        $periodeId = $request->periode_id ?? null;
        $status = $request->status ?? null;

        $periodes = LhkanPeriod::orderBy('tahun', 'desc')->get();

        $query = LhkanSubmission::where('instansi_id', $instansiId)
            ->with(['period', 'logs', 'pics']);

        if ($periodeId) {
            $query->where('periode_id', $periodeId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $submissions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Check if user has submitted in active period
        $currentPeriod = LhkanPeriod::open()->latest()->first();
        $hasSubmittedInActivePeriod = false;
        if ($currentPeriod) {
            $hasSubmittedInActivePeriod = LhkanSubmission::where('instansi_id', $instansiId)
                ->where('periode_id', $currentPeriod->id)
                ->whereIn('status', ['submitted', 'approved'])
                ->exists();
        }

        return view('lhkan.instansi.history', compact(
            'submissions',
            'periodes',
            'periodeId',
            'status',
            'currentPeriod',
            'hasSubmittedInActivePeriod'
        ));
    }

    /**
     * Store change request (for instansi)
     */
    public function changeRequestStore(Request $request)
    {
        if (!in_array($this->level, ['kl', 'provinsi', 'kabupaten'])) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $validated = $request->validate([
            'submission_id' => 'required|exists:lhkan_submissions,id',
            'reason' => 'required|string|max:500',
        ]);

        $submission = LhkanSubmission::findOrFail($validated['submission_id']);

        // Check if instansi owns this submission
        if ($submission->instansi_id !== $this->user->user_rel->instansi_id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Cek apakah sudah ada pending request
        if ($submission->isEditRequested()) {
            return redirect()->route('lhkan.history')->with('error', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }

        DB::beginTransaction();
        try {
            // Create change request
            LhkanChangeRequest::create([
                'submission_id' => $submission->id,
                'field_name' => 'all',
                'reason' => $validated['reason'],
                'status' => 'pending',
            ]);

            // Update status submission ke edit_requested
            $submission->requestEdit();

            // Log action
            LhkanLog::create([
                'submission_id' => $submission->id,
                'user_id' => $this->user->id,
                'action' => 'edit_requested',
                'description' => 'Pengajuan perubahan data diajukan',
                'ip_address' => request()->ip(),
            ]);

            DB::commit();
            return redirect()->route('lhkan.history')->with('success', 'Pengajuan perubahan berhasil dikirim. Menunggu persetujuan admin/TPN.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating change request: ' . $e->getMessage());
            return redirect()->route('lhkan.history')->with('error', 'Terjadi kesalahan saat mengirim pengajuan perubahan.');
        }
    }
}