<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\DokumenFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            foreach (allowed_url() as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    public function index()
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            return view('dokumen.admin');
        } else {
            $max_id = DokumenFile::max('id');
            $idx = $max_id > 0 ? $max_id + 1 : 1;
            return view('dokumen.index', compact('idx'));
        }
    }

    public function getDatas(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['provinsi', 'kabupaten', 'kl'])) {
            $instansi_id = $user->user_rel->instansi_id;
            $dokumens = Dokumen::where('instansi_id', $instansi_id)->get();
        } else {
            $model = new Dokumen();
            if ($request->instansi_id) {
                $model = $model->whereIn('instansi_id', $request->instansi_id);
            } 
            if ($request->tahun) {
                $model = $model->whereIn('tahun', $request->tahun);
            } 
            if ($request->kategori_id) {
                $model = $model->whereIn('kategori_id', $request->kategori_id);
            }
            if ($request->instansi_id || $request->tahun || $request->kategori_id) {
                $dokumens = $model->get();
            } else {
                $dokumens = $model->where('tahun', '-1')->get();
            }
        }
        foreach ($dokumens as $dokumen) {
            $dokumen->nama_kategori = $dokumen->kategori->nama;
            $dokumen->nama_instansi = $dokumen->instansi->name;
            $dokumen->filenya = '';
            foreach ($dokumen->files as $file) {
                $ext = pathinfo($file->file, PATHINFO_EXTENSION);
                $src = asset('storage/dokumen/' . $file->file);
                $dokumen->filenya .= '<a href="' . $src . '" target="_blank" title="' . $file->deskripsi . '" class="inline-block"><img src="' . asset('images') . '/'.exts($ext).'" style="width: 50px; margin-right: 5px; margin-top: 5px;"></a>';
            }
        }
        return response()->json(['data' => $dokumens]);
    }

    public function getData($tahun, $kategori_id)
    {
        $user = Auth::User();
        $dokumen = Dokumen::where('instansi_id', $user->user_rel->instansi_id)->where('tahun', $tahun)->where('kategori_id', $kategori_id)->first();
        if (!$dokumen) {
            $dokumen = new Dokumen();
        }
        $dokumen->file_list = '';
        foreach ($dokumen->files as $file) {
            $ext = pathinfo($file->file, PATHINFO_EXTENSION);
            $src = exts(strtolower($ext));
            $dokumen->dokumen_list .= '<div class="col-span-12 lg:col-span-4" id="dokumendiv'.$file->id.'" style="position:relative;">
                <div style="height: 100px;">
                    <img class="img-fluid card-img-top" src="'.asset('images').'/'.$src.'" alt="File'.$file->id.'" style="max-height: 100px; max-width:100%; padding: 5px 0;">
                </div>
                <div class="form-group mb-0">
                    <input type="text" name="deskripsi_existing['.$file->id.']" class="form-control filenya" id="deskripsi'.$file->id.'" placeholder="Deskripsi" value="'.$file->deskripsi.'">
                    <input type="hidden" name="file_existing['.$file->id.']" value="'.$file->file.'">
                </div>
                <a href="javascript:void(0);" onclick="removeFile('.$file->id.')" class="remove-button text-danger">
                    <div class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </div>
                </a>
            </div>';
        }
        return response()->json($dokumen);
    }

    public function simpan(Request $request)
    {
        $user = Auth::User();
        DB::beginTransaction();
        $success = true;
        try {
            $tahun = $request->tahun_edit ?? $request->tahun;
            $kategori_id = $request->kategori_id_edit ?? $request->kategori_id;
            $dokumen = Dokumen::where('instansi_id', $user->user_rel->instansi_id)->where('tahun', $tahun)->where('kategori_id', $kategori_id)->first();
            if (!$dokumen) {
                $dokumen = new Dokumen();
                $dokumen->instansi_id = $user->user_rel->instansi_id;
                $dokumen->tahun = $tahun;
                $dokumen->kategori_id = $kategori_id;
                $dokumen->save();
            }
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $dokumen_file) {
                    $ext = $dokumen_file->getClientOriginalExtension();
                    if (!in_array($ext, exts())) {
                        $success = false;
                    } else {
                        $file = new DokumenFile();
                        $file->dokumen_id = $dokumen->id;
                        $file->deskripsi = $request->deskripsi[$key];
                        $time = time();
                        $filename = $file->deskripsi."_$time." . $dokumen_file->getClientOriginalExtension();
                        $dokumen_file->storeAs('dokumen', $filename, 'public');
                        $file->file = $filename;
                        if (!$file->save()) {
                            $success = false;
                        }
                    }
                }
            }
            if ($success) {
                foreach ($dokumen->files as $file) {
                    if (!isset($request->file_existing[$file->id])) {
                        Storage::disk('public')->delete('dokumen/' . $file->file);
                        $file->delete();
                    } else {
                        $file->deskripsi = $request->deskripsi_existing[$file->id];
                        $file->save();
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $th->getMessage()]);
        }
        
        if ($success) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        return response()->json(['success' => $success]);
    }

    public function hapus(Request $request)
    {
        $dokumen = Dokumen::find($request->id);
        foreach ($dokumen->files as $file) {
            Storage::disk('public')->delete('dokumen/' . $file->file);
            $file->delete();
        }
        if ($dokumen->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
