<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreLhkanRequest extends FormRequest
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
            'periode_id' => 'required|exists:lhkan_periodes,id',
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
            'periode_id.required' => 'Periode wajib dipilih.',
            'periode_id.exists' => 'Periode tidak valid.',
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
            $totalPic = $this->jml_wajib_lhkpn + $this->jml_non_wajib_lhkpn;
            if ($totalPic !== $this->jml_aparatur) {
                $validator->errors()->add(
                    'jml_aparatur',
                    'Jumlah (Wajib LHKPN + Tidak Wajib LHKPN) harus sama dengan Total Aparatur.'
                );
            }

            // Validasi: realisasi tidak boleh lebih besar dari target
            if ($this->realisasi_lhkpn > $this->jml_wajib_lhkpn) {
                $validator->errors()->add(
                    'realisasi_lhkpn',
                    'Realisasi LHKPN tidak boleh lebih besar dari Jumlah Wajib LHKPN.'
                );
            }

            if ($this->realisasi_spt_non_lhkpn + $this->belum_spt_non_lhkpn > $this->jml_non_wajib_lhkpn) {
                $validator->errors()->add(
                    'realisasi_spt_non_lhkpn',
                    'Total (Realisasi SPT + Belum SPT) tidak boleh lebih besar dari Jumlah Non Wajib LHKPN.'
                );
            }
        });
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}