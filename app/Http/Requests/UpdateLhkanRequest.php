<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateLhkanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'jml_aparatur' => 'required|integer|min:0',
            'jml_wajib_lhkpn' => 'required|integer|min:0',
            'jml_non_wajib_lhkpn' => 'required|integer|min:0',
            'realisasi_lhkpn' => 'required|integer|min:0',
            'realisasi_spt_non_lhkpn' => 'required|integer|min:0',
            'belum_spt_non_lhkpn' => 'required|integer|min:0',
            'link_rekap_gdrive' => 'nullable|url',
            'catatan' => 'nullable|string|max:1000',
            'pics' => 'required|array|min:1',
            'pics.*.nama' => 'required|string|max:255',
            'pics.*.nomor_hp' => 'required|string|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'jml_aparatur.required' => 'Jumlah aparatur wajib diisi.',
            'jml_aparatur.integer' => 'Jumlah aparatur harus berupa angka.',
            'jml_aparatur.min' => 'Jumlah aparatur tidak boleh negatif.',
            'jml_wajib_lhkpn.required' => 'Jumlah wajib LHKPN wajib diisi.',
            'jml_non_wajib_lhkpn.required' => 'Jumlah non wajib LHKPN wajib diisi.',
            'realisasi_lhkpn.required' => 'Realisasi LHKPN wajib diisi.',
            'realisasi_spt_non_lhkpn.required' => 'Realisasi SPT non LHKPN wajib diisi.',
            'belum_spt_non_lhkpn.required' => 'Belum SPT non LHKPN wajib diisi.',
            'link_rekap_gdrive.url' => 'Format link rekap tidak valid.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
            'pics.required' => 'Minimal satu PIC harus ditambahkan.',
            'pics.min' => 'Minimal satu PIC harus ditambahkan.',
            'pics.*.nama.required' => 'Nama PIC wajib diisi.',
            'pics.*.nomor_hp.required' => 'Nomor HP PIC wajib diisi.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Validasi: jml_wajib_lhkpn + jml_non_wajib_lhkpn harus sama dengan jml_aparatur
            // Cast ke int karena input form selalu string
            $jmlAparatur = (int) $this->jml_aparatur;
            $jmlWajib = (int) $this->jml_wajib_lhkpn;
            $jmlNonWajib = (int) $this->jml_non_wajib_lhkpn;
            if (($jmlWajib + $jmlNonWajib) !== $jmlAparatur) {
                $validator->errors()->add(
                    'jml_aparatur',
                    'Jumlah (Wajib LHKPN + Tidak Wajib LHKPN) harus sama dengan Total Aparatur.'
                );
            }

            // Validasi: realisasi tidak boleh lebih besar dari target
            $realisasiLhkpn = (int) $this->realisasi_lhkpn;
            if ($realisasiLhkpn > $jmlWajib) {
                $validator->errors()->add(
                    'realisasi_lhkpn',
                    'Realisasi LHKPN tidak boleh lebih besar dari Jumlah Wajib LHKPN.'
                );
            }

            $realisasiSpt = (int) $this->realisasi_spt_non_lhkpn;
            $belumSpt = (int) $this->belum_spt_non_lhkpn;
            if (($realisasiSpt + $belumSpt) > $jmlNonWajib) {
                $validator->errors()->add(
                    'realisasi_spt_non_lhkpn',
                    'Total (Realisasi SPT + Belum SPT) tidak boleh lebih besar dari Jumlah Non Wajib LHKPN.'
                );
            }
        });
    }
}