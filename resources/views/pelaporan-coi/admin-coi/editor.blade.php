@extends('layout.rubick')
@section('title', 'Modul COI - Editor Formulir')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <style>
        /* Desain Kotak Input Standar (Untuk Pertanyaan & Opsi) */
        .editable-input { 
            border: 1px solid transparent !important; 
            border-radius: 8px !important;
            transition: all 0.2s ease; 
            background-color: transparent;
            box-sizing: border-box;
        }
        .editable-input:hover { 
            background-color: #f8fafc; 
            border: 1px solid #e2e8f0 !important; 
        }
        .editable-input:focus { 
            outline: none !important; 
            background-color: #ffffff;
            border: 1px solid #2563eb !important; 
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
        }
        .editable-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
            opacity: 0.7;
        }

        /* Desain Input Khusus Judul & Deskripsi (Gaya Google Forms) */
        .header-input {
            width: 100%;
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important; /* Garis bawah selalu tampil */
            border-radius: 0 !important;
            background-color: transparent;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        .header-input:hover {
            background-color: #f8fafc;
            border-bottom: 1px solid #cbd5e1 !important;
        }
        .header-input:focus {
            outline: none !important;
            background-color: #f8fafc;
            border-bottom: 2px solid #2563eb !important; /* Garis bawah biru tebal saat aktif */
            box-shadow: none !important; 
        }
        .header-input::placeholder {
            color: #94a3b8;
        }
        
        .custom-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%232563eb'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1em; }
        
        /* Tombol X Hapus Opsi (Tampil abu-abu, hover jadi merah) */
        .option-row .delete-btn { 
            display: block; 
            color: #cbd5e1;
            font-size: 22px; 
            cursor: pointer; 
            padding: 0 8px; 
            transition: color 0.2s; 
            line-height: 1;
        }
        .option-row .delete-btn:hover { 
            color: #ef4444;
        }
        
        .drag-handle { cursor: grab; }
        .drag-handle:active { cursor: grabbing; }
    </style>

    <div style="max-width: 850px; margin: 0 auto; padding-top: 20px; padding-bottom: 50px; font-family: 'Inter', sans-serif;">
        
        {{-- KOTAK HEADER FORMULIR (Gaya Google Forms) --}}
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-top: 8px solid #2563eb; border-radius: 12px; margin-bottom: 24px; padding: 30px 24px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1); position: relative;">
            
            {{-- Badge ADMIN di pojok kanan atas dalam kotak --}}
            <div style="position: absolute; top: 16px; right: 24px; padding: 4px 16px; border-radius: 9999px; background-color: #fefce8; color: #ca8a04; border: 1px solid #fde047; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">
                ADMIN
            </div>

            {{-- Input Judul --}}
            <input type="text" placeholder="Judul Formulir" class="header-input" onfocus="this.select()" style="font-size: 28px; font-weight: 600; color: #1e293b; padding: 8px 0; margin-bottom: 12px;">
            
            {{-- Input Deskripsi --}}
            <input type="text" placeholder="Deskripsi formulir" class="header-input" onfocus="this.select()" style="color: #64748b; font-size: 14px; padding: 8px 0;">
        </div>

        {{-- AREA PERTANYAAN (Wadah untuk Drag & Drop) --}}
        <div id="questions-container">
            
            {{-- CARD PERTANYAAN PERTAMA --}}
            <div class="question-card" data-id="q_1" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-left: 6px solid #2563eb; border-radius: 12px; margin-bottom: 24px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div class="drag-handle" style="display: flex; align-items: center; color: #94a3b8; font-size: 12px; font-weight: 700; letter-spacing: 1px;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="margin-right: 8px;"><path d="M8 6a2 2 0 11-4 0 2 2 0 014 0zM8 12a2 2 0 11-4 0 2 2 0 014 0zM8 18a2 2 0 11-4 0 2 2 0 014 0zM20 6a2 2 0 11-4 0 2 2 0 014 0zM20 12a2 2 0 11-4 0 2 2 0 014 0zM20 18a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="question-number-label">PERTANYAAN 1</span>
                    </div>
                    <span class="badge-wajib" style="display: none; color: #ef4444; background-color: #fef2f2; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600;">Wajib</span>
                </div>

                {{-- Input Teks Pertanyaan --}}
                <input type="text" placeholder="Tulis pertanyaan di sini..." class="editable-input question-title" onfocus="this.select()" style="width: 100%; font-size: 16px; font-weight: 600; color: #1e293b; padding: 12px 16px; margin-bottom: 20px;">

                <div style="margin-bottom: 20px;">
                    <select class="custom-select" onchange="changeQuestionType(this)" style="padding: 10px 36px 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; color: #475569; font-weight: 500; font-size: 14px; outline: none; background-color: #ffffff; cursor: pointer;">
                        <option value="pilihan_ganda" selected>Pilihan Ganda</option>
                        <option value="jawaban_singkat">Jawaban Singkat</option>
                        <option value="jawaban_panjang">Jawaban Panjang</option>
                        <option value="kotak_centang">Kotak Centang</option>
                    </select>
                </div>

                <div class="options-container" style="padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
                    {{-- Opsi Pilihan Ganda --}}
                    <div class="type-view" data-type="pilihan_ganda" style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="option-list">
                            <div class="option-row" style="display: flex; align-items: center; margin-bottom: 8px;">
                                <div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 50%; margin-right: 8px;"></div>
                                <input type="text" value="Opsi 1" placeholder="Ketik teks opsi..." class="editable-input" onfocus="this.select()" style="flex: 1; font-size: 14px; color: #334155; padding: 10px 12px; margin-right: 8px;">
                                <span class="delete-btn" onclick="deleteOption(this)">×</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; margin-top: 8px; cursor: pointer; padding-left: 4px;" onclick="addOption(this, 'pilihan_ganda')">
                            <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="margin-right: 12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span style="font-size: 14px; color: #94a3b8;">Tambah opsi</span>
                        </div>
                    </div>

                    {{-- Opsi Teks (Singkat/Panjang) --}}
                    <div class="type-view" data-type="jawaban_singkat" style="display: none;">
                        <div style="border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px; color: #94a3b8; font-size: 14px; width: 60%; margin-left: 4px;">Teks jawaban singkat</div>
                    </div>
                    <div class="type-view" data-type="jawaban_panjang" style="display: none;">
                        <div style="border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px; color: #94a3b8; font-size: 14px; width: 100%; margin-left: 4px;">Teks jawaban panjang</div>
                        <div style="border-bottom: 1px dashed #cbd5e1; padding-top: 16px; width: 100%;"></div>
                    </div>

                    {{-- Opsi Kotak Centang --}}
                    <div class="type-view" data-type="kotak_centang" style="display: none; flex-direction: column; gap: 8px;">
                        <div class="option-list">
                            <div class="option-row" style="display: flex; align-items: center; margin-bottom: 8px;">
                                <div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 4px; margin-right: 8px;"></div>
                                <input type="text" value="Opsi 1" placeholder="Ketik teks opsi..." class="editable-input" onfocus="this.select()" style="flex: 1; font-size: 14px; color: #334155; padding: 10px 12px; margin-right: 8px;">
                                <span class="delete-btn" onclick="deleteOption(this)">×</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; margin-top: 8px; cursor: pointer; padding-left: 4px;" onclick="addOption(this, 'kotak_centang')">
                            <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="margin-right: 12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span style="font-size: 14px; color: #94a3b8;">Tambah opsi</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px;">
                    <button type="button" onclick="toggleWajib(this)" data-active="false" style="display: flex; align-items: center; gap: 8px; border: 1px solid #cbd5e1; background-color: #f1f5f9; color: #64748b; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                        Jadikan Wajib
                    </button>
                    <div style="display: flex; gap: 16px; color: #94a3b8;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="cursor: pointer;" onclick="duplicateQuestion(this)" title="Duplikat"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="cursor: pointer;" onclick="deleteQuestion(this)" title="Hapus"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- TOMBOL TAMBAH PERTANYAAN --}}
        <button type="button" onclick="addNewQuestion()" style="width: 100%; border: 1px dashed #94a3b8; background-color: #f8fafc; color: #64748b; font-weight: 500; padding: 16px; border-radius: 12px; display: flex; justify-content: center; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'; this.style.borderColor='#64748b'" onmouseout="this.style.backgroundColor='#f8fafc'; this.style.borderColor='#94a3b8'">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pertanyaan
        </button>

        {{-- FOOTER --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 40px;">
            <span id="question-count-text" style="color: #94a3b8; font-size: 13px;">1 pertanyaan</span>
            <button style="background-color: #2563eb; color: white; padding: 12px 32px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                Simpan Formulir
            </button>
        </div>
    </div>

    {{-- Script Logika Form Builder --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('questions-container');
            new Sortable(container, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-slate-50', 
                onEnd: function () {
                    updateQuestionNumbers();
                }
            });
            updateQuestionNumbers();
        });

        function updateQuestionNumbers() {
            const cards = document.querySelectorAll('.question-card');
            cards.forEach((card, index) => {
                const label = card.querySelector('.question-number-label');
                if (label) {
                    label.innerText = `PERTANYAAN ${index + 1}`;
                }
            });
            document.getElementById('question-count-text').innerText = `${cards.length} pertanyaan`;
        }

        function changeQuestionType(selectObj) {
            const card = selectObj.closest('.question-card');
            const selectedType = selectObj.value;
            const views = card.querySelectorAll('.type-view');

            views.forEach(view => {
                if(view.getAttribute('data-type') === selectedType) {
                    view.style.display = (selectedType.includes('jawaban')) ? 'block' : 'flex';
                } else {
                    view.style.display = 'none';
                }
            });
        }

        function deleteOption(btnObj) {
            const optionList = btnObj.closest('.option-list');
            if(optionList.querySelectorAll('.option-row').length > 1) {
                btnObj.closest('.option-row').remove();
            } else {
                alert("Minimal harus ada 1 opsi!");
            }
        }

        function addOption(btnObj, type) {
            const viewContainer = btnObj.closest('.type-view');
            const optionList = viewContainer.querySelector('.option-list');
            const currentOptionsCount = optionList.querySelectorAll('.option-row').length + 1;
            
            const iconHtml = type === 'pilihan_ganda' 
                ? `<div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 50%; margin-right: 8px;"></div>`
                : `<div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 4px; margin-right: 8px;"></div>`;

            const newRow = document.createElement('div');
            newRow.className = 'option-row';
            newRow.style.cssText = 'display: flex; align-items: center; margin-bottom: 8px;';
            newRow.innerHTML = `
                ${iconHtml}
                <input type="text" value="Opsi ${currentOptionsCount}" placeholder="Ketik teks opsi..." class="editable-input" onfocus="this.select()" style="flex: 1; font-size: 14px; color: #334155; padding: 10px 12px; margin-right: 8px;">
                <span class="delete-btn" onclick="deleteOption(this)">×</span>
            `;
            
            optionList.appendChild(newRow);
            newRow.querySelector('input').focus(); 
        }

        function toggleWajib(btnObj) {
            const card = btnObj.closest('.question-card');
            const badge = card.querySelector('.badge-wajib');
            const isActive = btnObj.getAttribute('data-active') === 'true';

            if(isActive) {
                btnObj.setAttribute('data-active', 'false');
                btnObj.innerHTML = 'Jadikan Wajib';
                btnObj.style.backgroundColor = '#f1f5f9';
                btnObj.style.color = '#64748b';
                btnObj.style.borderColor = '#cbd5e1';
                badge.style.display = 'none';
            } else {
                btnObj.setAttribute('data-active', 'true');
                btnObj.innerHTML = 'Wajib <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-left:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                btnObj.style.backgroundColor = '#fef2f2';
                btnObj.style.color = '#ef4444';
                btnObj.style.borderColor = '#fca5a5';
                badge.style.display = 'inline-block';
            }
        }

        function addNewQuestion() {
            const container = document.getElementById('questions-container');
            const templateCard = container.querySelector('.question-card').cloneNode(true);
            
            templateCard.querySelector('.question-title').value = "Pertanyaan baru";
            templateCard.querySelector('.custom-select').value = "pilihan_ganda";
            changeQuestionType(templateCard.querySelector('.custom-select'));
            
            const btnWajib = templateCard.querySelector('button[onclick="toggleWajib(this)"]');
            btnWajib.setAttribute('data-active', 'true');
            toggleWajib(btnWajib);

            container.appendChild(templateCard);
            updateQuestionNumbers();
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }

        function duplicateQuestion(btnObj) {
            const cardToCopy = btnObj.closest('.question-card');
            const clonedCard = cardToCopy.cloneNode(true);
            cardToCopy.parentNode.insertBefore(clonedCard, cardToCopy.nextSibling);
            updateQuestionNumbers();
        }

        function deleteQuestion(btnObj) {
            const container = document.getElementById('questions-container');
            if(container.querySelectorAll('.question-card').length > 1) {
                btnObj.closest('.question-card').remove();
                updateQuestionNumbers();
            } else {
                alert("Minimal harus ada 1 pertanyaan dalam formulir!");
            }
        }
    </script>
@endsection