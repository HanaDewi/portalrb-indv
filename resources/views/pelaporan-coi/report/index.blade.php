@extends('layout.rubick')
@section('title', 'Modul COI')

@section('content')
    {{-- CSS Khusus Form --}}
    <style>
        .coi-option {
            transition: all 0.2s;
        }
        .coi-option:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }
        .coi-option.selected {
            background-color: #eff6ff !important;
            border-color: #2563eb !important;
        }
        .question-card {
            cursor: default;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <div style="width: 90%; margin: 0 auto; padding-top: 20px; padding-bottom: 50px; font-family: 'Inter', sans-serif;">
        
        {{-- Header Section --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div>
                {{-- Judul Dinamis --}}
                <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0;">
                    {{ $formHeader ? $formHeader->teks_pertanyaan : 'Pelaporan COI' }}
                </h2>
                {{-- Deskripsi Dinamis --}}
                <p style="color: #64748b; margin-top: 4px; font-size: 14px;">
                    {{ $formHeader && isset($formHeader->opsi[0]) ? $formHeader->opsi[0] : 'Mode Pengguna — isi formulir di bawah' }}
                </p>
            </div>
            <div style="padding: 4px 16px; border-radius: 9999px; background-color: #ecfdf5; color: #059669; border: 1px solid #6ee7b7; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">
                PENGGUNA
            </div>
        </div>

        {{-- Progress Bar Dinamis --}}
        <div style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <span style="color: #94a3b8; font-size: 14px; font-weight: 500;">Progres pengisian</span>
                <span id="progress-text" style="color: #94a3b8; font-size: 14px; font-weight: 500;">0 / {{ count($questions) }}</span>
            </div>
            <div style="width: 100%; height: 8px; background-color: #e2e8f0; border-radius: 9999px; overflow: hidden;">
                <div id="progress-fill" style="width: 0%; height: 100%; background-color: #2563eb; border-radius: 9999px; transition: width 0.4s ease-in-out;"></div>
            </div>
        </div>

        {{-- Menampilkan Error Jika Validasi Gagal --}}
        @if(session('error'))
            <div style="padding: 16px; background-color: #fef2f2; color: #ef4444; border: 1px solid #fca5a5; border-radius: 8px; margin-bottom: 24px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Logika Pengecekan Status Form --}}
        @php
            // Cek apakah user sudah punya jawaban di database
            $hasAnswered = count($savedAnswers) > 0;
            // Tampilkan Card jika baru saja submit ATAU sudah pernah submit sebelumnya
            $showSuccess = session('success') || $hasAnswered;
        @endphp

        {{-- SUCCESS STATE MODERN (Pop Up Sukses / Sudah Mengisi) --}}
        @if($showSuccess)
            <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 48px 24px; text-align: center; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); margin-bottom: 24px; animation: slideDown 0.5s ease-out;">
                <div style="width: 72px; height: 72px; background-color: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg width="36" height="36" fill="none" stroke="#15803d" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                
                {{-- Judul dinamis: Beda teks saat baru klik simpan vs saat buka ulang web --}}
                <h3 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 12px; letter-spacing: -0.5px;">
                    {{ session('success') ? 'Pelaporan Berhasil Disimpan' : 'Anda Telah Mengisi Formulir Ini' }}
                </h3>
                
                <p style="color: #64748b; font-size: 15px; margin-bottom: 32px; max-width: 450px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                    Jawaban Anda telah tersimpan dengan aman di dalam sistem. Anda dapat melihat kembali atau mengubah jawaban sebelum batas waktu pelaporan berakhir.
                </p>
                <button onclick="document.getElementById('coi-form-container').style.display='block'; this.parentElement.style.display='none';" style="background-color: #f8fafc; color: #334155; padding: 12px 28px; border-radius: 9999px; font-weight: 600; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.2s; font-size: 14px;">
                    Tinjau / Edit Jawaban
                </button>
            </div>
        @endif

        {{-- CONTAINER FORM --}}
        <div id="coi-form-container" style="{{ $showSuccess ? 'display: none;' : 'display: block;' }}">
            <form id="coi-form" action="{{ route('coi.user.submit') }}" method="POST">
                @csrf
                
                @foreach ($questions as $q)
                    @php
                        // Ambil jawaban dari database, jika tidak ada default ke null
                        $jawabanUser = $savedAnswers[$q->id] ?? null;

                        // PASTIKAN $jawabanArray selalu bertipe array, jangan sampai null
                        $decoded = json_decode($jawabanUser, true);
                        $jawabanArray = is_array($decoded) ? $decoded : [];
                    @endphp

                    <div class="intro-y question-card" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <div style="height: 6px; background-color: #2563eb; width: 100%;"></div>
                        
                        <div style="padding: 24px;">
                            <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                                <div style="flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%; background-color: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; margin-right: 16px; margin-top: 2px;">
                                    {{ $loop->iteration }}
                                </div>
                                <label style="font-weight: 600; color: #1e293b; font-size: 16px; line-height: 1.5; pointer-events: none;">
                                    {{ $q->teks_pertanyaan }} 
                                    @if($q->is_wajib)
                                        <span style="color: #ef4444;">*</span>
                                    @endif
                                </label>
                            </div>

                            <div class="options-container" style="margin-left: 44px; display: flex; flex-direction: column; gap: 12px;">
                                
                                @if($q->tipe_jawaban == 'pilihan_ganda' || $q->tipe_jawaban == 'kotak_centang')
                                    @if($q->opsi)
                                        @foreach($q->opsi as $opt)
                                            <label class="coi-option {{ ($jawabanUser == $opt || in_array($opt, $jawabanArray)) ? 'selected' : '' }}" style="display: flex; align-items: center; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background-color: #ffffff;">
                                                
                                                @if($q->tipe_jawaban == 'pilihan_ganda')
                                                    <input type="radio" 
                                                           name="jawaban[{{ $q->id }}]" 
                                                           value="{{ $opt }}" 
                                                           {{ $q->is_wajib && !$jawabanUser ? 'required' : '' }}
                                                           {{ $jawabanUser == $opt ? 'checked' : '' }} 
                                                           style="width: 18px; height: 18px; margin-right: 12px; accent-color: #2563eb; cursor: pointer;">
                                                @else
                                                    <input type="checkbox" 
                                                           name="jawaban[{{ $q->id }}][]" 
                                                           value="{{ $opt }}" 
                                                           {{ in_array($opt, $jawabanArray) ? 'checked' : '' }} 
                                                           style="width: 18px; height: 18px; margin-right: 12px; accent-color: #2563eb; cursor: pointer;">
                                                @endif
                                                
                                                <span style="color: #334155; font-size: 15px; font-weight: 500;">{{ $opt }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                    
                                @elseif($q->tipe_jawaban == 'jawaban_singkat')
                                    <input type="text" name="jawaban[{{ $q->id }}]" value="{{ $jawabanUser }}" {{ $q->is_wajib && !$jawabanUser ? 'required' : '' }} placeholder="Ketik jawaban Anda..." style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 14px;">
                                    
                                @elseif($q->tipe_jawaban == 'jawaban_panjang')
                                    <textarea name="jawaban[{{ $q->id }}]" rows="4" {{ $q->is_wajib && !$jawabanUser ? 'required' : '' }} placeholder="Ketik jawaban panjang Anda..." style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 14px; resize: vertical;">{{ $jawabanUser }}</textarea>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 24px;">
                    <span style="color: #94a3b8; font-size: 14px;">Pastikan semua pertanyaan wajib sudah diisi sebelum menyimpan.</span>
                    <button id="submit-btn" type="submit" 
                            style="background-color: #2563eb; color: white; padding: 12px 32px; border-radius: 9999px; font-weight: 600; border: none; cursor: pointer; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                        Kirim / Perbarui Jawaban
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script untuk Progress Bar dan Efek UI --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('coi-form');
            const progressText = document.getElementById('progress-text');
            const progressFill = document.getElementById('progress-fill');
            const totalQuestions = {{ count($questions) }};

            function updateProgressAndUI() {
                const answeredNames = new Set();

                // 1. Urus UI Radio dan Checkbox (Warna Background Biru)
                document.querySelectorAll('.coi-option input').forEach(input => {
                    const label = input.closest('label');
                    if (input.checked) {
                        label.classList.add('selected');
                        answeredNames.add(input.name.replace('[]', '')); // Hitung progress
                    } else {
                        label.classList.remove('selected');
                    }
                });

                // 2. Hitung progress dari input teks dan textarea
                document.querySelectorAll('input[type="text"], textarea').forEach(input => {
                    if (input.value.trim() !== '') {
                        answeredNames.add(input.name);
                    }
                });

                // 3. Update Text & Bar
                const count = answeredNames.size;
                progressText.innerText = count + ' / ' + totalQuestions;
                
                const percentage = totalQuestions > 0 ? (count / totalQuestions) * 100 : 0;
                progressFill.style.width = percentage + '%';
            }

            // Jalankan saat ada perubahan di form
            form.addEventListener('change', updateProgressAndUI);
            form.addEventListener('input', updateProgressAndUI);

            // Jalankan sekali saat halaman baru di-load untuk mengecek jawaban dari database
            updateProgressAndUI();
        });
    </script>
@endsection