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
    </style>

    {{-- Form Container: Lebar 90% dan posisinya di tengah layar --}}
    <div style="width: 90%; margin: 0 auto; padding-top: 20px; padding-bottom: 50px; font-family: 'Inter', sans-serif;">
        
        {{-- Header Section --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div>
                <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0;">Pelaporan COI</h2>
                <p style="color: #64748b; margin-top: 4px; font-size: 14px;">Mode Pengguna — isi formulir di bawah</p>
            </div>
            <div style="padding: 4px 16px; border-radius: 9999px; background-color: #ecfdf5; color: #059669; border: 1px solid #6ee7b7; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">
                PENGGUNA
            </div>
        </div>

        {{-- Progress Bar Dinamis --}}
        <div style="margin-bottom: 30px;">
            {{-- Teks Progress --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <span style="color: #94a3b8; font-size: 14px; font-weight: 500;">Progres pengisian</span>
                <span id="progress-text" style="color: #94a3b8; font-size: 14px; font-weight: 500;">0 / {{ count($questions) }}</span>
            </div>
            {{-- Garis Background Progress --}}
            <div style="width: 100%; height: 8px; background-color: #e2e8f0; border-radius: 9999px; overflow: hidden;">
                {{-- Garis Biru Fill --}}
                <div id="progress-fill" style="width: 0%; height: 100%; background-color: #2563eb; border-radius: 9999px; transition: width 0.4s ease-in-out;"></div>
            </div>
        </div>

        @if(session('success'))
            <div style="padding: 16px; background-color: #dcfce7; color: #15803d; border-radius: 8px; margin-bottom: 24px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Mulai --}}
        <form id="coi-form" action="#" method="POST">
            @csrf
            
            @foreach ($questions as $key => $row)
                {{-- Card Pertanyaan --}}
                <div class="intro-y question-card" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    
                    {{-- Garis Biru Tebal di Atas --}}
                    <div style="height: 6px; background-color: #2563eb; width: 100%;"></div>
                    
                    <div style="padding: 24px;">
                        {{-- Nomor & Teks Pertanyaan --}}
                        <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                            <div style="flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%; background-color: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; margin-right: 16px; margin-top: 2px;">
                                {{ $loop->iteration }}
                            </div>
                            <label style="font-weight: 600; color: #1e293b; font-size: 16px; line-height: 1.5; pointer-events: none;">
                                {{ $row['label'] }} <span style="color: #ef4444;">*</span>
                            </label>
                        </div>

                        {{-- Pilihan Ganda --}}
                        <div class="options-container" style="margin-left: 44px; display: flex; flex-direction: column; gap: 12px;">
                            {{-- Opsi Ya --}}
                            <label class="coi-option" style="display: flex; align-items: center; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background-color: #ffffff;">
                                <input type="radio" name="{{ $key }}" value="1" required 
                                       style="width: 18px; height: 18px; margin-right: 12px; accent-color: #2563eb; cursor: pointer;">
                                <span style="color: #334155; font-size: 15px; font-weight: 500;">Ya</span>
                            </label>

                            {{-- Opsi Tidak --}}
                            <label class="coi-option" style="display: flex; align-items: center; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background-color: #ffffff;">
                                <input type="radio" name="{{ $key }}" value="0" required 
                                       style="width: 18px; height: 18px; margin-right: 12px; accent-color: #2563eb; cursor: pointer;">
                                <span style="color: #334155; font-size: 15px; font-weight: 500;">Tidak</span>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Footer Form --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 24px;">
                <span style="color: #94a3b8; font-size: 14px;">Pastikan semua pertanyaan wajib sudah diisi</span>
                <button id="submit-btn" type="submit" 
                        style="background-color: #2563eb; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                    Kirim Jawaban
                </button>
            </div>
        </form>
    </div>

    {{-- Script Logika Form --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('coi-form');
            const radios = form.querySelectorAll('input[type="radio"]');
            const progressText = document.getElementById('progress-text');
            const progressFill = document.getElementById('progress-fill');
            const totalQuestions = {{ count($questions) }};
            const storageKey = 'coi_draft_answers';

            const savedAnswers = JSON.parse(localStorage.getItem(storageKey)) || {};

            function updateProgress() {
                const answered = new Set();
                radios.forEach(r => {
                    if (r.checked) answered.add(r.name);
                });
                
                const count = answered.size;
                progressText.innerText = count + ' / ' + totalQuestions;
                
                // Menghitung persentase dan mengubah lebar progress bar
                const percentage = totalQuestions > 0 ? (count / totalQuestions) * 100 : 0;
                progressFill.style.width = percentage + '%';
            }

            function updateUI() {
                radios.forEach(r => {
                    const label = r.closest('label');
                    if (r.checked) {
                        label.classList.add('selected');
                    } else {
                        label.classList.remove('selected');
                    }
                });
            }

            radios.forEach(r => {
                if (savedAnswers[r.name] === r.value) {
                    r.checked = true;
                }

                r.addEventListener('change', function() {
                    savedAnswers[this.name] = this.value;
                    localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
                    updateUI();
                    updateProgress();
                });
            });

            updateUI();
            updateProgress();

            document.querySelectorAll('.question-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('.options-container')) {
                        const cardRadios = this.querySelectorAll('input[type="radio"]');
                        if (cardRadios.length > 0) {
                            const name = cardRadios[0].name;
                            
                            cardRadios.forEach(r => r.checked = false);
                            
                            delete savedAnswers[name];
                            localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
                            
                            updateUI();
                            updateProgress();
                        }
                    }
                });
            });

            form.addEventListener('submit', function() {
                localStorage.removeItem(storageKey);
            });
        });
    </script>
@endsection