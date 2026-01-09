@extends('layout.rubick')
@section('title', 'Dashboard')

@section('content')
    @if (in_array(auth()->user()->level, ['kl', 'provinsi', 'kabupaten']) && coi_finalized(auth()->user()->instansi_id) == false)
        <div id="popupOverlay" class="popup-overlay" style="display: none;">
            <div class="popup-container">
                <button class="popup-close" onclick="closePopup()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <div class="popup-left">
                    <div class="popup-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#FF0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>

                    <h2 class="popup-title">Pengumuman Penting</h2>
                    <div class="popup-content">
                        <p>Merujuk <strong>Surat Edaran Menteri PANRB Nomor 6 Tahun 2025</strong> tentang Pelaksanaan Reformasi Birokrasi pada Masa Transisi, Instansi Pemerintah diharapkan dapat mengisi capaian rencana aksi Triwulan IV paling lambat <strong>30 Januari 2026</strong>.</p>

                        <p style="margin-top: 15px;">Untuk melengkapi pengisian capaian tersebut, Instansi Pemerintah wajib terlebih dahulu mengisi kuesioner implementasi pengelolaan konflik kepentingan pada sub-menu <strong>Pelaporan CoI.</strong>, sebagaimana ketentuan yang tercantum dalam Surat Deputi pada tautan berikut: <a href="https://bit.ly/4swlKF6" target="_blank" style="color: #3b82f6; text-decoration: underline;">https://bit.ly/4swlKF6</a>
                            terima kasih.
                        </p>
                    </div>
                    <button class="popup-button" onclick="window.open('{{ url('pelaporan-coi') }}', '_blank')">
                        Isi Kuesioner
                    </button>
                </div>
                <div class="popup-right">
                    <img src="{{ URL::to('/') }}/assets/images/popup.png" alt="Pengumuman" class="popup-image">
                </div>
            </div>
        </div>

        <style>
            .popup-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 9999;
                display: flex;
                justify-content: center;
                align-items: center;
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            .popup-container {
                background-color: white;
                border-radius: 12px;
                max-width: 900px;
                width: 90%;
                position: relative;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                animation: slideDown 0.3s ease-in-out;
                display: flex;
                overflow: hidden;
            }

            @keyframes slideDown {
                from {
                    transform: translateY(-50px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .popup-close {
                position: absolute;
                top: 15px;
                right: 15px;
                background: none;
                border: none;
                cursor: pointer;
                color: #64748b;
                padding: 5px;
                transition: all 0.2s;
                border-radius: 6px;
                z-index: 10;
            }

            .popup-close:hover {
                background-color: #f1f5f9;
                color: #1e293b;
            }

            .popup-left {
                width: 60%;
                padding: 40px 30px 30px 30px;
            }

            .popup-right {
                width: 40%;
                position: relative;
            }

            .popup-image {
                width: 100%;
                height: 100%;
                object-fit: contain;
                border-radius: 0 12px 12px 0;
            }

            @media (max-width: 768px) {
                .popup-left {
                    width: 100%;
                }

                .popup-right {
                    display: none;
                }
            }

            .popup-icon {
                text-align: center;
                margin-bottom: 20px;
            }

            .popup-title {
                font-size: 24px;
                font-weight: bold;
                color: #1e293b;
                text-align: center;
                margin-bottom: 20px;
            }

            .popup-content {
                color: #475569;
                line-height: 1.7;
                text-align: justify;
                margin-bottom: 25px;
                font-size: 15px;
            }

            .popup-button {
                width: 100%;
                background-color: #3A4551;
                color: #FFFFFF;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }

            .popup-button:hover {
                background-color: #a8c8e8;
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .popup-button:active {
                transform: translateY(0);
            }
        </style>

        <script>
            function closePopup() {
                document.getElementById('popupOverlay').style.display = 'none';
            }
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('popupOverlay').style.display = 'flex';
            });

            window.addEventListener('click', function(e) {
                if (e.target.id === 'popupOverlay') {
                    closePopup();
                }
            });
        </script>
    @endif

    <div class="col-span-12 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 md:col-span-12 lg:col-span-12 xl:col-span-12">
            <div class="box">
                <div class="p-5">
                    <div class="rounded-md">
                        <img width="100%" alt="menpanrb" class="rounded-md" src="{{ URL::to('/') }}/assets/images/banner_dashboard.jpg">
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
        <div class="box p-5 zoom-in">
            <a href="rencanaaksiuser.html">
                <div class="flex items-center">
                    <div class="w-2/4 flex-none">
                        <div class="text-lg font-bold truncate">Data Rekap RB General</div>
                        <div class="text-slate-500 mt-1">Data Perencanaan Aksi RB General</div>
                    </div>
                    <div class="flex-none ml-auto relative">
                        <div class="w-[90px] h-[90px]">
                            <canvas id="report-donut-chart-1" width="90" height="90"
                                style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                        </div>
                        <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                            <i data-lucide="pie-chart" class="w-10 h-10"> </i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
        <div class="box p-5 zoom-in">
            <a href="tematik.html">
                <div class="flex items-center">
                    <div class="w-2/4 flex-none">
                        <div class="text-lg font-bold truncate">Data Rekap RB Tematik</div>
                        <div class="text-slate-500 mt-1">Data Perencanaan Aksi RB Tematik</div>
                    </div>
                    <div class="flex-none ml-auto relative">
                        <div class="w-[90px] h-[90px]">
                            <canvas id="report-donut-chart-2" width="90" height="90"
                                style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                        </div>
                        <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                            <span><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-file-bar-chart">
                                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <path d="M12 18v-4" />
                                    <path d="M8 18v-2" />
                                    <path d="M16 18v-6" />
                                </svg> </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
        <div class="box p-5 zoom-in">
            <a href="rb-general/perencanaan">
                <div class="flex items-center">
                    <div class="w-2/4 flex-none">
                        <div class="text-lg font-bold truncate">Input Rencana Aksi</div>
                        <div class="text-slate-500 mt-1">Masukan Rencana Aksi RB 2023</div>
                    </div>
                    <div class="flex-none ml-auto relative">
                        <div class="w-[90px] h-[90px]">
                        </div>
                        <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                            <div
                                class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 radius10">
                                <button class="btn btn-warning mr-1 mb-2 radius10" style="border-radius: 15px;"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-scroll-text">
                                        <path d="M8 21h12a2 2 0 0 0 2-2v-2H10v2a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v3h4" />
                                        <path d="M19 17V5a2 2 0 0 0-2-2H4" />
                                        <path d="M15 8h-5" />
                                        <path d="M15 12h-5" />
                                    </svg></button>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
        <div class="box p-5 zoom-in">
            <a href="inputrencanatematik.html">
                <div class="flex items-center">
                    <div class="w-2/4 flex-none">
                        <div class="text-lg font-bold truncate">Input Rencana Tematik</div>
                        <div class="text-slate-500 mt-1">Masukan Rencana Tematik RB General 2023</div>
                    </div>
                    <div class="flex-none ml-auto relative">
                        <div class="w-[90px] h-[90px]">
                        </div>
                        <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                            <div
                                class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 radius10">
                                <button class="btn btn-pending mr-1 mb-2" style="border-radius: 15px;"> <i
                                        data-lucide="file-text" class="w-10 h-10"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div> --}}
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ url('hasil') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Hasil Evaluasi</div>
                            <div class="text-slate-500 mt-1">Hasil Evaluasi Reformasi Birokrasi Tahun 2023</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                                <canvas id="report-donut-chart-2" width="90" height="90" style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-bar-chart">
                                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <path d="M12 18v-4" />
                                        <path d="M8 18v-2" />
                                        <path d="M16 18v-6" />
                                    </svg> </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('profil') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Pengaturan</div>
                            <div class="text-slate-500 mt-1">Pengaturan Profil dan Akun</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 radius10">
                                    <button class="btn btn-dark mr-1 mb-2" style="border-radius: 15px;"> <i data-lucide="settings" class="w-10 h-10"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Logout</div>
                            <div class="text-slate-500 mt-1">Keluar Dari Sistem</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 ">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 ">
                                    <button class="btn btn-danger mr-1 mb-2 radius10" style="border-radius: 15px;"> <i data-lucide="log-out" class="w-10 h-10"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
