<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\KlpdInstansi;
use App\Models\ZI\TahunEvaluasi;
use App\Models\ZI\TahapSeleksiZI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ZIHelper
{
    /* ======================================================
     * DATE & TAHAP
     * ====================================================== */

    public static function lastZIYear(): int
    {
        $tahun = TahunEvaluasi::orderByDesc('tahun')->first();

        if (!$tahun) {
            throw new \Exception('Data Tahun Evaluasi belum tersedia');
        }

        return (int) $tahun->tahun;
    }

    public static function activeYear(): int
    {
        return (int) (
            Session::get('tahun_zi')
            ?? self::lastZIYear()
        );
    }

    public static function resolveInstansi(Request $request): ?KlpdInstansi
    {
        if (ZIHelper::isAdminOrTpn() && $request->instansi_id) {
            return KlpdInstansi::find($request->instansi_id);
        }

        return Auth::user()->user_rel->instansi
            ?? KlpdInstansi::find(Auth::user()->instansi_id);
    }

    public static function getTahapSeleksi(int $tahun, string $tahap): ?TahapSeleksiZI
    {
        return TahapSeleksiZI::where('tahun', $tahun)
            ->where('tahap_seleksi', $tahap)
            ->first();
    }

    public static function isPeriodeOpen(TahapSeleksiZI $tahap): bool
    {
        return now()->between(
            Carbon::parse($tahap->tanggal_mulai),
            Carbon::parse($tahap->tanggal_selesai)
        );
    }

    public static function isPeriodeStarted(TahapSeleksiZI $tahap): bool
    {
        return now()->gte(Carbon::parse($tahap->tanggal_mulai));
    }

    /* ======================================================
     * AUTH & ROLE
     * ====================================================== */

    public static function isAdminOrTpn(): bool
    {
        if (!Auth::check()) return false;

        return in_array(Auth::user()->level, ['admin', 'tpn']);
    }

    /* ======================================================
     * URL NORMALIZER
     * ====================================================== */

    public static function normalizeUrl(?string $url): ?string
    {
        if (!$url) return null;

        return 'http://' . preg_replace('#^.*://#', '', $url);
    }
}
