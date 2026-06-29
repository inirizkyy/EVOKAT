@extends('layouts.frontend')
@section('title', 'Informasi Layanan')

@section('content')
<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Informasi Layanan E-Advokat</h1>
            <div class="w-24 h-1 bg-brand mx-auto rounded-full"></div>
        </div>
        
        <div class="bg-neutral-primary-soft rounded-[2rem] shadow-md border border-border-default p-8 sm:p-12 relative overflow-hidden">
            <!-- Decorative Gavel Watermark -->
            <div class="absolute -right-12 top-20 text-brand opacity-5 text-[20rem] pointer-events-none select-none -rotate-12">
                <i class="fa-solid fa-gavel"></i>
            </div>
            
            <div class="mb-14 relative z-10">
                <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-lg"><i class="fa-solid fa-circle-info"></i></span>
                    Penjelasan Layanan
                </h4>
                <div class="space-y-4 text-body leading-relaxed text-[15px] bg-white p-6 rounded-2xl border border-border-default shadow-sm border-l-4 border-l-brand">
                    <p>Layanan E-Advokat adalah sistem elektronik yang disediakan oleh Pengadilan Tinggi Tanjungkarang untuk memfasilitasi calon advokat dalam proses permohonan pengambilan sumpah profesi advokat sesuai dengan amanat Undang-Undang Nomor 18 Tahun 2003 tentang Advokat.</p>
                    <p>Sistem ini dirancang untuk mewujudkan asas peradilan yang cepat, sederhana, dan biaya ringan, serta meningkatkan transparansi pelayanan publik.</p>
                </div>
            </div>
            
            <div class="mb-14 relative z-10">
                <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-lg"><i class="fa-solid fa-scale-balanced"></i></span>
                    Dasar Hukum
                </h4>
                <ul class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <li class="flex flex-col items-center text-center gap-3 p-6 bg-white rounded-2xl border border-border-default shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-brand/30 transition-all group">
                        <div class="w-14 h-14 rounded-full bg-brand/5 text-brand flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all">
                            <i class="fa-solid fa-gavel"></i>
                        </div>
                        <span class="text-[14px] text-heading font-semibold leading-snug">Undang-Undang Nomor 18 Tahun 2003 tentang Advokat</span>
                    </li>
                    <li class="flex flex-col items-center text-center gap-3 p-6 bg-white rounded-2xl border border-border-default shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-brand/30 transition-all group">
                        <div class="w-14 h-14 rounded-full bg-brand/5 text-brand flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <span class="text-[14px] text-heading font-semibold leading-snug">Peraturan Mahkamah Agung (PERMA) yang mengatur pelaksanaan sumpah advokat</span>
                    </li>
                    <li class="flex flex-col items-center text-center gap-3 p-6 bg-white rounded-2xl border border-border-default shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-brand/30 transition-all group">
                        <div class="w-14 h-14 rounded-full bg-brand/5 text-brand flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all">
                            <i class="fa-solid fa-scroll"></i>
                        </div>
                        <span class="text-[14px] text-heading font-semibold leading-snug">Surat Keputusan Ketua Mahkamah Agung terkait persyaratan dan mekanisme sumpah</span>
                    </li>
                </ul>
            </div>

            <div class="relative z-10">
                <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-8 flex items-center gap-3 border-b border-border-default pb-4">
                    <span class="w-10 h-10 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-lg"><i class="fa-solid fa-list-check"></i></span>
                    Mekanisme Pelayanan
                </h4>
                <div class="relative border-l-2 border-brand/20 ml-6 space-y-8 pb-4">
                    <div class="relative pl-10 group">
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full bg-white border-2 border-brand shadow-sm flex items-center justify-center text-brand font-bold text-[16px] group-hover:bg-brand group-hover:text-white transition-colors">1</div>
                        <div class="bg-white p-5 rounded-xl border border-border-default shadow-sm hover:shadow-md transition-shadow">
                            <p class="text-[15px] text-body leading-relaxed"><strong class="text-heading">Pengisian Formulir:</strong> Pemohon mengisi formulir pendaftaran secara online melalui situs web E-Advokat.</p>
                        </div>
                    </div>
                    <div class="relative pl-10 group">
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full bg-white border-2 border-brand shadow-sm flex items-center justify-center text-brand font-bold text-[16px] group-hover:bg-brand group-hover:text-white transition-colors">2</div>
                        <div class="bg-white p-5 rounded-xl border border-border-default shadow-sm hover:shadow-md transition-shadow">
                            <p class="text-[15px] text-body leading-relaxed"><strong class="text-heading">Unggah Berkas:</strong> Mengunggah semua dokumen persyaratan dalam format digital (PDF/JPG) dengan ukuran maksimal 2MB per file.</p>
                        </div>
                    </div>
                    <div class="relative pl-10 group">
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full bg-white border-2 border-brand shadow-sm flex items-center justify-center text-brand font-bold text-[16px] group-hover:bg-brand group-hover:text-white transition-colors">3</div>
                        <div class="bg-white p-5 rounded-xl border border-border-default shadow-sm hover:shadow-md transition-shadow">
                            <p class="text-[15px] text-body leading-relaxed"><strong class="text-heading">Nomor Registrasi:</strong> Mendapatkan Nomor Registrasi sebagai bukti pengajuan untuk tracking.</p>
                        </div>
                    </div>
                    <div class="relative pl-10 group">
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full bg-white border-2 border-brand shadow-sm flex items-center justify-center text-brand font-bold text-[16px] group-hover:bg-brand group-hover:text-white transition-colors">4</div>
                        <div class="bg-white p-5 rounded-xl border border-border-default shadow-sm hover:shadow-md transition-shadow">
                            <p class="text-[15px] text-body leading-relaxed"><strong class="text-heading">Verifikasi Berkas:</strong> Petugas Pengadilan Tinggi akan melakukan verifikasi administrasi terhadap berkas yang diunggah.</p>
                        </div>
                    </div>
                    <div class="relative pl-10 group">
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full bg-white border-2 border-brand shadow-sm flex items-center justify-center text-brand font-bold text-[16px] group-hover:bg-brand group-hover:text-white transition-colors">5</div>
                        <div class="bg-white p-5 rounded-xl border border-border-default shadow-sm hover:shadow-md transition-shadow">
                            <p class="text-[15px] text-body leading-relaxed"><strong class="text-heading">Penjadwalan:</strong> Jika berkas dinyatakan lengkap dan memenuhi syarat, pemohon akan dijadwalkan untuk pengambilan sumpah.</p>
                        </div>
                    </div>
                    <div class="relative pl-10 group">
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full bg-white border-2 border-brand shadow-sm flex items-center justify-center text-brand font-bold text-[16px] group-hover:bg-brand group-hover:text-white transition-colors">6</div>
                        <div class="bg-white p-5 rounded-xl border border-border-default shadow-sm hover:shadow-md transition-shadow">
                            <p class="text-[15px] text-body leading-relaxed"><strong class="text-heading">Pengambilan Sumpah:</strong> Pengambilan sumpah dilakukan pada jadwal yang telah ditetapkan di Pengadilan Tinggi dengan membawa berkas asli untuk pencocokan.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
