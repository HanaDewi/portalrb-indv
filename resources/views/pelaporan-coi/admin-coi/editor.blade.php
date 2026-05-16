@extends('layout.rubick')
@section('title', 'Modul COI - Editor Formulir')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <style>
        /* Desain Kotak Input Standar */
        .editable-input { border: 1px solid transparent !important; border-radius: 8px !important; transition: all 0.2s ease; background-color: transparent; box-sizing: border-box; }
        .editable-input:hover { background-color: #f8fafc; border: 1px solid #e2e8f0 !important; }
        .editable-input:focus { outline: none !important; background-color: #ffffff; border: 1px solid #2563eb !important; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important; }
        .editable-input::placeholder { color: #94a3b8; font-weight: 400; opacity: 0.7; }

        /* Desain Input Judul */
        .header-input { width: 100%; border: none !important; border-bottom: 1px solid #e2e8f0 !important; border-radius: 0 !important; background-color: transparent; transition: all 0.2s ease; box-sizing: border-box; }
        .header-input:hover { background-color: #f8fafc; border-bottom: 1px solid #cbd5e1 !important; }
        .header-input:focus { outline: none !important; background-color: #f8fafc; border-bottom: 2px solid #2563eb !important; box-shadow: none !important; }
        
        .custom-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%232563eb'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1em; }
        
        .option-row .delete-btn { display: block; color: #cbd5e1; font-size: 22px; cursor: pointer; padding: 0 8px; transition: color 0.2s; line-height: 1; }
        .option-row .delete-btn:hover { color: #ef4444; }
        
        .drag-handle { cursor: grab; }
        .drag-handle:active { cursor: grabbing; }
    </style>

    {{-- Toast Notification --}}
    <div id="toast-notification" style="position: fixed; top: 24px; right: 24px; padding: 16px 24px; border-radius: 12px; color: white; display: flex; align-items: center; gap: 12px; z-index: 9999; opacity: 0; transform: translateY(-20px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2); pointer-events: none;">
        <div id="toast-icon" style="display: flex; align-items: center;"></div>
        <span id="toast-message" style="font-weight: 600; font-size: 15px; letter-spacing: 0.3px;">Pesan</span>
    </div>

    <div style="max-width: 850px; margin: 0 auto; padding-top: 20px; padding-bottom: 50px; font-family: 'Inter', sans-serif;">
        
        {{-- TAB NAVIGASI ADMIN (Editor vs Respons) --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div style="display: flex; gap: 8px; background: #e2e8f0; padding: 4px; border-radius: 10px; width: fit-content;">
                {{-- Tab Editor (Aktif) --}}
                <a href="{{ route('coi.admin.index') }}" style="padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; background: white; color: #1e293b; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editor Formulir
                </a>
                {{-- Tab Respons --}}
                <a href="{{ route('coi.admin.responses') }}" style="padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; background: transparent; color: #64748b; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Data Respons
                </a>
            </div>

            {{-- Quick Stats Card (Kanan) --}}
            <a href="{{ route('coi.admin.responses') }}" style="text-decoration: none; background: white; padding: 8px 16px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" onmouseover="this.style.borderColor='#2563eb'" onmouseout="this.style.borderColor='#e2e8f0'">
                <div style="background: #eff6ff; padding: 6px; border-radius: 8px; color: #2563eb; display: flex;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <div>
                    <p style="margin: 0; font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Respons Masuk</p>
                    <h5 style="margin: 0; font-size: 16px; font-weight: 700; color: #1e293b;">{{ $totalResponses }} <span style="font-size: 12px; color: #94a3b8; font-weight: 400; margin-left: 2px;">Instansi</span></h5>
                </div>
            </a>
        </div>

        {{-- KOTAK HEADER FORMULIR --}}
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-top: 8px solid #2563eb; border-radius: 12px; margin-bottom: 24px; padding: 30px 24px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1); position: relative;">
            <div style="position: absolute; top: 16px; right: 24px; padding: 4px 16px; border-radius: 9999px; background-color: #fefce8; color: #ca8a04; border: 1px solid #fde047; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">ADMIN</div>
            <input type="text" id="form-title-input" placeholder="Judul Formulir" class="header-input" onfocus="this.select()" style="font-size: 28px; font-weight: 600; color: #1e293b; padding: 8px 0; margin-bottom: 12px;">
            <input type="text" id="form-desc-input" placeholder="Deskripsi formulir" class="header-input" onfocus="this.select()" style="color: #64748b; font-size: 14px; padding: 8px 0;">
        </div>

        {{-- AREA CONTAINER PERTANYAAN (Akan diisi JS) --}}
        <div id="questions-container"></div>

        <button type="button" onclick="addNewQuestion()" style="width: 100%; border: 1px dashed #94a3b8; background-color: #f8fafc; color: #64748b; font-weight: 500; padding: 16px; border-radius: 12px; display: flex; justify-content: center; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pertanyaan
        </button>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 40px;">
            <span id="question-count-text" style="color: #94a3b8; font-size: 13px;">0 pertanyaan</span>
            <button onclick="saveForm()" style="background-color: #2563eb; color: white; padding: 12px 32px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                Simpan Formulir
            </button>
        </div>
    </div>

    {{-- TEMPLATE CARD RAHASIA (Dipakai JS untuk clone) --}}
    <div id="card-template" style="display: none;">
        <div class="question-card" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-left: 6px solid #2563eb; border-radius: 12px; margin-bottom: 24px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div class="drag-handle" style="display: flex; align-items: center; color: #94a3b8; font-size: 12px; font-weight: 700; letter-spacing: 1px;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="margin-right: 8px;"><path d="M8 6a2 2 0 11-4 0 2 2 0 014 0zM8 12a2 2 0 11-4 0 2 2 0 014 0zM8 18a2 2 0 11-4 0 2 2 0 014 0zM20 6a2 2 0 11-4 0 2 2 0 014 0zM20 12a2 2 0 11-4 0 2 2 0 014 0zM20 18a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="question-number-label">PERTANYAAN</span>
                </div>
                <span class="badge-wajib" style="display: none; color: #ef4444; background-color: #fef2f2; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600;">Wajib</span>
            </div>

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
                <div class="type-view" data-type="pilihan_ganda" style="display: flex; flex-direction: column; gap: 8px;">
                    <div class="option-list">
                        <div class="option-row" style="display: flex; align-items: center; margin-bottom: 8px;">
                            <div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 50%; margin-right: 8px;"></div>
                            <input type="text" value="Opsi 1" class="editable-input" onfocus="this.select()" style="flex: 1; font-size: 14px; color: #334155; padding: 10px 12px;">
                            <span class="delete-btn" onclick="deleteOption(this)">×</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 8px; cursor: pointer; padding-left: 4px;" onclick="addOption(this, 'pilihan_ganda')">
                        <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="margin-right: 12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span style="font-size: 14px; color: #94a3b8;">Tambah opsi</span>
                    </div>
                </div>

                <div class="type-view" data-type="jawaban_singkat" style="display: none;">
                    <div style="border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px; color: #94a3b8; font-size: 14px; width: 60%;">Teks jawaban singkat</div>
                </div>
                
                <div class="type-view" data-type="jawaban_panjang" style="display: none;">
                    <div style="border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px; color: #94a3b8; font-size: 14px; width: 100%;">Teks jawaban panjang</div>
                </div>

                <div class="type-view" data-type="kotak_centang" style="display: none; flex-direction: column; gap: 8px;">
                    <div class="option-list">
                        <div class="option-row" style="display: flex; align-items: center; margin-bottom: 8px;">
                            <div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 4px; margin-right: 8px;"></div>
                            <input type="text" value="Opsi 1" class="editable-input" onfocus="this.select()" style="flex: 1; font-size: 14px; color: #334155; padding: 10px 12px;">
                            <span class="delete-btn" onclick="deleteOption(this)">×</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 8px; cursor: pointer; padding-left: 4px;" onclick="addOption(this, 'kotak_centang')">
                        <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="margin-right: 12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span style="font-size: 14px; color: #94a3b8;">Tambah opsi</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px;">
                <button type="button" onclick="toggleWajib(this)" data-active="false" style="display: flex; align-items: center; gap: 8px; border: 1px solid #cbd5e1; background-color: #f1f5f9; color: #64748b; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;">Jadikan Wajib</button>
                <div style="display: flex; gap: 16px; color: #94a3b8;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="cursor: pointer;" onclick="duplicateQuestion(this)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="cursor: pointer;" onclick="deleteQuestion(this)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('questions-container');
            new Sortable(container, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: () => { updateQuestionNumbers(); autoSaveDraft(); }
            });

            // 1. DATA INITIALIZATION (Prioritas: Draft > DB > Kosong)
            const rawDB = @json(isset($existingQuestions) ? $existingQuestions : []);
            const draftRaw = localStorage.getItem('coi_admin_draft');
            let dataToLoad = [];

            if (draftRaw) {
                try { dataToLoad = JSON.parse(draftRaw); } catch(e) {}
                showToast("Draft yang belum disave berhasil dimuat ulang", "success");
            } else if (rawDB && rawDB.length > 0) {
                // Parsing dari Database
                dataToLoad = rawDB.map(q => {
                    let parsedOptions = [];
                    if (typeof q.opsi === 'string') { try { parsedOptions = JSON.parse(q.opsi); } catch(e) {} } 
                    else if (Array.isArray(q.opsi)) { parsedOptions = q.opsi; }
                    
                    return {
                        title: q.teks_pertanyaan,
                        type: q.tipe_jawaban,
                        options: parsedOptions,
                        required: (q.is_wajib === 1 || q.is_wajib === true)
                    };
                });
            }

            // 2. RENDER FORM
            if (dataToLoad.length > 0) {
                const headerData = dataToLoad.find(q => q.type === 'form_header');
                if (headerData) {
                    document.getElementById('form-title-input').value = headerData.title || '';
                    document.getElementById('form-desc-input').value = headerData.options[0] || '';
                }

                const questionsOnly = dataToLoad.filter(q => q.type !== 'form_header');
                if (questionsOnly.length > 0) {
                    questionsOnly.forEach(q => renderCardFromData(q));
                } else {
                    addNewQuestion();
                }
            } else {
                addNewQuestion(); // Render 1 kosong kalau DB dan draft gak ada
            }

            updateQuestionNumbers();

            // 3. LISTENERS UNTUK AUTO-SAVE DRAFT
            document.body.addEventListener('input', autoSaveDraft);
            document.body.addEventListener('change', autoSaveDraft);
        });

        // ================= FUNGSI LOGIKA UTAMA =================

        let draftTimeout;
        function autoSaveDraft() {
            clearTimeout(draftTimeout);
            draftTimeout = setTimeout(() => {
                localStorage.setItem('coi_admin_draft', JSON.stringify(getFormData()));
            }, 800); // Auto save setelah ngetik beres (800ms)
        }

        function getFormData() {
            const questions = [];
            const formTitle = document.getElementById('form-title-input').value || 'Pelaporan COI';
            const formDesc = document.getElementById('form-desc-input').value || 'Mode Pengguna — isi formulir di bawah';

            questions.push({ title: formTitle, type: 'form_header', options: [formDesc], required: false });

            document.querySelectorAll('#questions-container .question-card').forEach((card) => {
                const title = card.querySelector('.question-title').value;
                const type = card.querySelector('.custom-select').value;
                const isRequired = card.querySelector('button[onclick="toggleWajib(this)"]').getAttribute('data-active') === 'true';
                
                let options = [];
                if (type === 'pilihan_ganda' || type === 'kotak_centang') {
                    const activeView = card.querySelector(`.type-view[data-type="${type}"]`);
                    activeView.querySelectorAll('.option-list input').forEach(input => {
                        if (input.value.trim() !== "") options.push(input.value);
                    });
                }
                questions.push({ title, type, options, required: isRequired });
            });
            return questions;
        }

        function saveForm() {
            const questions = getFormData();
            
            fetch("{{ route('coi.admin.save') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ questions: questions })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Terjadi kesalahan');
                return data;
            })
            .then(data => {
                showToast("Formulir berhasil disimpan ke Database!", 'success');
                localStorage.removeItem('coi_admin_draft'); // Hapus draft karena udah disave
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message || "Gagal menyimpan formulir.", 'error');
            });
        }

        // ================= FUNGSI DOM & UI =================

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast-notification');
            const msgEl = document.getElementById('toast-message');
            const iconEl = document.getElementById('toast-icon');

            msgEl.innerText = message;
            if (type === 'success') {
                toast.style.backgroundColor = '#10b981';
                iconEl.innerHTML = '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else {
                toast.style.backgroundColor = '#ef4444';
                iconEl.innerHTML = '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }

            toast.style.opacity = '1'; toast.style.transform = 'translateY(0)';
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateY(-20px)'; }, 3000);
        }

        function updateQuestionNumbers() {
            const cards = document.querySelectorAll('#questions-container .question-card');
            cards.forEach((card, index) => {
                const label = card.querySelector('.question-number-label');
                if (label) label.innerText = `PERTANYAAN ${index + 1}`;
            });
            document.getElementById('question-count-text').innerText = `${cards.length} pertanyaan`;
        }

        function renderCardFromData(data) {
            const templateCard = document.getElementById('card-template').firstElementChild.cloneNode(true);
            
            templateCard.querySelector('.question-title').value = data.title;
            templateCard.querySelector('.custom-select').value = data.type;
            
            const btnWajib = templateCard.querySelector('button[onclick="toggleWajib(this)"]');
            if (data.required) {
                btnWajib.setAttribute('data-active', 'false'); 
                toggleWajib(btnWajib);
            }

            const views = templateCard.querySelectorAll('.type-view');
            views.forEach(view => {
                if(view.getAttribute('data-type') === data.type) {
                    view.style.display = (data.type.includes('jawaban')) ? 'block' : 'flex';
                    if (data.type === 'pilihan_ganda' || data.type === 'kotak_centang') {
                        const list = view.querySelector('.option-list');
                        list.innerHTML = '';
                        const iconHtml = data.type === 'pilihan_ganda' ? `<div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 50%; margin-right: 8px;"></div>` : `<div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 4px; margin-right: 8px;"></div>`;
                        
                        data.options.forEach((optValue) => {
                            const newRow = document.createElement('div');
                            newRow.className = 'option-row';
                            newRow.style.cssText = 'display: flex; align-items: center; margin-bottom: 8px;';
                            newRow.innerHTML = `${iconHtml}<input type="text" value="${optValue}" class="editable-input" onfocus="this.select()" style="flex: 1; font-size: 14px; color: #334155; padding: 10px 12px; margin-right: 8px;"><span class="delete-btn" onclick="deleteOption(this)">×</span>`;
                            list.appendChild(newRow);
                        });
                    }
                } else {
                    view.style.display = 'none';
                }
            });

            document.getElementById('questions-container').appendChild(templateCard);
        }

        function addNewQuestion() {
            const templateCard = document.getElementById('card-template').firstElementChild.cloneNode(true);
            templateCard.querySelector('.question-title').value = "Pertanyaan baru";
            templateCard.querySelector('.custom-select').value = "pilihan_ganda";
            changeQuestionType(templateCard.querySelector('.custom-select'));
            document.getElementById('questions-container').appendChild(templateCard);
            updateQuestionNumbers();
            autoSaveDraft();
        }

        function changeQuestionType(selectObj) {
            const card = selectObj.closest('.question-card');
            const selectedType = selectObj.value;
            card.querySelectorAll('.type-view').forEach(view => {
                view.style.display = (view.getAttribute('data-type') === selectedType) ? (selectedType.includes('jawaban') ? 'block' : 'flex') : 'none';
            });
            autoSaveDraft();
        }

        function deleteOption(btnObj) {
            const optionList = btnObj.closest('.option-list');
            if(optionList.querySelectorAll('.option-row').length > 1) {
                btnObj.closest('.option-row').remove();
                autoSaveDraft();
            } else { alert("Minimal harus ada 1 opsi!"); }
        }

        function addOption(btnObj, type) {
            const list = btnObj.closest('.type-view').querySelector('.option-list');
            const iconHtml = type === 'pilihan_ganda' ? `<div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 50%; margin-right: 8px;"></div>` : `<div style="width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 4px; margin-right: 8px;"></div>`;
            const newRow = document.createElement('div');
            newRow.className = 'option-row';
            newRow.style.cssText = 'display: flex; align-items: center; margin-bottom: 8px;';
            newRow.innerHTML = `${iconHtml}<input type="text" value="Opsi ${list.children.length + 1}" class="editable-input" onfocus="this.select()" style="flex: 1; font-size: 14px; color: #334155; padding: 10px 12px; margin-right: 8px;"><span class="delete-btn" onclick="deleteOption(this)">×</span>`;
            list.appendChild(newRow);
            newRow.querySelector('input').focus();
            autoSaveDraft();
        }

        function toggleWajib(btnObj) {
            const badge = btnObj.closest('.question-card').querySelector('.badge-wajib');
            if(btnObj.getAttribute('data-active') === 'true') {
                btnObj.setAttribute('data-active', 'false');
                btnObj.innerHTML = 'Jadikan Wajib'; btnObj.style.backgroundColor = '#f1f5f9'; btnObj.style.color = '#64748b'; btnObj.style.borderColor = '#cbd5e1';
                badge.style.display = 'none';
            } else {
                btnObj.setAttribute('data-active', 'true');
                btnObj.innerHTML = 'Wajib <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-left:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                btnObj.style.backgroundColor = '#fef2f2'; btnObj.style.color = '#ef4444'; btnObj.style.borderColor = '#fca5a5';
                badge.style.display = 'inline-block';
            }
            autoSaveDraft();
        }

        function duplicateQuestion(btnObj) {
            const card = btnObj.closest('.question-card');
            card.parentNode.insertBefore(card.cloneNode(true), card.nextSibling);
            updateQuestionNumbers(); autoSaveDraft();
        }

        function deleteQuestion(btnObj) {
            const container = document.getElementById('questions-container');
            if(container.querySelectorAll('.question-card').length > 1) {
                btnObj.closest('.question-card').remove();
                updateQuestionNumbers(); autoSaveDraft();
            } else { alert("Minimal harus ada 1 pertanyaan!"); }
        }
    </script>
@endsection