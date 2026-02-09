<?php

namespace App\Http\Controllers\AKIP;

use App\Http\Controllers\Controller;
use App\Models\Akip\Penghargaan;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PenghargaanController extends Controller
{
    protected $currentUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = auth()->user();
            $allowed_urls = allowed_url('akip');

            // Check if URL matches any allowed pattern
            foreach ($allowed_urls as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }

            // Additional check for penghargaan routes
            if ($request->is('akip/penghargaan*')) {
                return $next($request);
            }

            abort('403');
        });
    }

    /**
     * Display a single penghargaan record
     */
    public function show($id)
    {
        $penghargaan = Penghargaan::with('instansi')->find($id);

        if (!$penghargaan) {
            return response()->json([
                'success' => false,
                'message' => 'Data penghargaan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $penghargaan
        ]);
    }

    /**
     * Display the penghargaan page
     */
    public function index(Request $request)
    {
        // Filter berdasarkan tahun dengan validasi
        $tahun = $request->input('tahun', date('Y'));
        $tahun = is_numeric($tahun) && $tahun >= 2020 && $tahun <= date('Y') ? (int) $tahun : date('Y');

        // Get all instansi with penghargaan for the selected year
        $query = Penghargaan::with('instansi')
            ->where('tahun', $tahun)
            ->whereNull('deleted_at');

        // Apply search filter if provided
        $search = $request->input('search', '');
        if (!empty($search)) {
            $query->whereHas('instansi', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $penghargaan = $query->orderBy('id', 'desc')->get();

        return view('akip.penghargaan.index', compact(
            'penghargaan',
            'tahun',
            'search'
        ));
    }

    /**
     * Store a newly created penghargaan
     */
    public function store(Request $request)
    {
        // Check if user has permission (TPN only)
        if (!in_array($this->currentUser->level, ['tpn', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menambah data penghargaan'
            ], 403);
        }

        try {
            // Validate request
            $request->validate([
                'instansi_id' => 'required|exists:klpd_instansi_new,id',
                'tahun' => 'required|integer|min:2020|max:' . date('Y'),
                'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            ]);

            // Check unique constraint
            $exists = Penghargaan::where('instansi_id', $request->instansi_id)
                ->where('tahun', $request->tahun)
                ->whereNull('deleted_at')
                ->first();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Instansi ini sudah memiliki sertifikat untuk tahun ' . $request->tahun
                ]);
            }

            // Handle file upload
            if ($request->hasFile('file_sertifikat')) {
                $file = $request->file('file_sertifikat');

                // Normalisasi nama file
                $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $safeName = Str::slug($original, '_') . '.' . $ext;

                $filename = time() . '_' . $safeName;
                $file->storeAs('akip/penghargaan', $filename, 'public');
            }

            // Create penghargaan record
            $penghargaan = new Penghargaan();
            $penghargaan->instansi_id = $request->instansi_id;
            $penghargaan->tahun = $request->tahun;
            $penghargaan->file_sertifikat = $filename;
            $penghargaan->save();

            return response()->json([
                'success' => true,
                'message' => 'Data penghargaan berhasil ditambahkan'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified penghargaan
     */
    public function update(Request $request, $id)
    {
        // Check if user has permission (TPN only)
        if (!in_array($this->currentUser->level, ['tpn', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah data penghargaan'
            ], 403);
        }

        try {
            $penghargaan = Penghargaan::find($id);
            if (!$penghargaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data penghargaan tidak ditemukan'
                ], 404);
            }

            // Validate request
            $request->validate([
                'instansi_id' => 'required|exists:klpd_instansi_new,id',
                'tahun' => 'required|integer|min:2020|max:' . date('Y'),
                'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            // Check unique constraint (excluding current record)
            $exists = Penghargaan::where('instansi_id', $request->instansi_id)
                ->where('tahun', $request->tahun)
                ->where('id', '!=', $id)
                ->whereNull('deleted_at')
                ->first();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Instansi ini sudah memiliki sertifikat untuk tahun ' . $request->tahun
                ]);
            }

            // Handle file upload if new file is provided
            if ($request->hasFile('file_sertifikat')) {
                // Delete old file
                if (!empty($penghargaan->file_sertifikat)) {
                    Storage::disk('public')->delete('akip/penghargaan/' . $penghargaan->file_sertifikat);
                }

                $file = $request->file('file_sertifikat');
                $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $safeName = Str::slug($original, '_') . '.' . $ext;

                $filename = time() . '_' . $safeName;
                $file->storeAs('akip/penghargaan', $filename, 'public');
                $penghargaan->file_sertifikat = $filename;
            }

            // Update penghargaan record
            $penghargaan->instansi_id = $request->instansi_id;
            $penghargaan->tahun = $request->tahun;
            $penghargaan->save();

            return response()->json([
                'success' => true,
                'message' => 'Data penghargaan berhasil diperbarui'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified penghargaan
     */
    public function destroy($id)
    {
        // Check if user has permission (TPN only)
        if (!in_array($this->currentUser->level, ['tpn', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus data penghargaan'
            ], 403);
        }

        try {
            $penghargaan = Penghargaan::find($id);
            if (!$penghargaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data penghargaan tidak ditemukan'
                ], 404);
            }

            // Delete file from storage
            if (!empty($penghargaan->file_sertifikat)) {
                Storage::disk('public')->delete('akip/penghargaan/' . $penghargaan->file_sertifikat);
            }

            // Soft delete the record
            $penghargaan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data penghargaan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download sertifikat file
     */
    public function download($id)
    {
        try {
            $penghargaan = Penghargaan::find($id);
            if (!$penghargaan || empty($penghargaan->file_sertifikat)) {
                abort(404, 'File tidak ditemukan');
            }

            $filePath = 'akip/penghargaan/' . $penghargaan->file_sertifikat;

            if (!Storage::disk('public')->exists($filePath)) {
                abort(404, 'File tidak ditemukan di storage');
            }

            return Storage::disk('public')->download($filePath, $penghargaan->file_sertifikat);

        } catch (\Exception $e) {
            abort(500, 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Check for duplicate instansi-year combination
     */
    public function checkTahun(Request $request)
    {
        $exists = Penghargaan::where('instansi_id', $request->instansi_id)
            ->where('tahun', $request->tahun)
            ->whereNull('deleted_at')
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }
}
