@extends('layouts.frontend')
@section('title', 'Alur Pengajuan')

@section('content')
<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)] relative z-20">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-info/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        
        <div class="text-center mb-16 relative">
            <h1 class="font-['Playfair_Display'] text-4xl md:text-5xl font-bold text-heading mb-6 relative inline-block">
                Alur Pengajuan <span class="text-brand">Sumpah Advokat</span>
                <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-brand-softer via-brand to-brand-softer rounded-full"></div>
            </h1>
            <p class="text-body-subtle mt-8 max-w-2xl mx-auto text-lg">Proses terstruktur dan transparan untuk memudahkan layanan mulai dari registrasi online hingga pelaksanaan sumpah dan pengambilan BAS.</p>
        </div>
        
        <div class="relative mt-12 pl-4 sm:pl-0">
            <!-- Left-aligned Line -->
            <div class="absolute left-[2.2rem] sm:left-12 top-0 bottom-0 w-1 bg-gradient-to-b from-brand via-info to-success rounded-full opacity-20"></div>
            
            <div class="space-y-12">
                
                <!-- Step 1 -->
                <div class="relative flex items-start group">
                    <div class="absolute left-0 sm:left-4 w-16 h-16 rounded-full bg-neutral-primary shadow-sm border-2 border-brand flex items-center justify-center z-10 text-brand text-2xl font-['Playfair_Display'] font-bold group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                        1
                    </div>
                    
                    <div class="ml-20 sm:ml-32 w-full">
                        <div class="bg-neutral-primary-soft rounded-2xl p-6 sm:p-8 shadow-sm border border-border-default hover:shadow-md hover:border-brand/30 transition-all relative w-full duration-300">
                            <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-3">Isi Formulir</h4>
                            <p class="text-[15px] text-body leading-relaxed">Pemohon mengisi form pendaftaran secara online dengan melengkapi data diri yang valid pada menu <strong>Ajukan Permohonan</strong>. Pastikan semua data diketik dengan benar sesuai identitas KTP.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex items-start group">
                    <div class="absolute left-0 sm:left-4 w-16 h-16 rounded-full bg-neutral-primary shadow-sm border-2 border-brand flex items-center justify-center z-10 text-brand text-2xl font-['Playfair_Display'] font-bold group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                        2
                    </div>
                    
                    <div class="ml-20 sm:ml-32 w-full">
                        <div class="bg-neutral-primary-soft rounded-2xl p-6 sm:p-8 shadow-sm border border-border-default hover:shadow-md hover:border-brand/30 transition-all relative w-full duration-300">
                            <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-3">Upload Dokumen</h4>
                            <p class="text-[15px] text-body leading-relaxed">Unggah seluruh berkas persyaratan yang ditentukan ke dalam format digital (PDF/JPG) dengan batas ukuran maksimal 2MB per file. Berkas harus dapat dibaca dengan jelas.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="relative flex items-start group">
                    <div class="absolute left-0 sm:left-4 w-16 h-16 rounded-full bg-neutral-primary shadow-sm border-2 border-brand flex items-center justify-center z-10 text-brand text-2xl font-['Playfair_Display'] font-bold group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                        3
                    </div>
                    
                    <div class="ml-20 sm:ml-32 w-full">
                        <div class="bg-neutral-primary-soft rounded-2xl p-6 sm:p-8 shadow-sm border border-border-default hover:shadow-md hover:border-brand/30 transition-all relative w-full duration-300">
                            <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-3">Verifikasi Berkas</h4>
                            <p class="text-[15px] text-body leading-relaxed">Admin dari Pengadilan Tinggi akan melakukan pengecekan keabsahan dan kelengkapan dokumen yang diunggah. Pemohon dapat memantau status secara langsung melalui fitur <strong>Tracking</strong>.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Step 4 -->
                <div class="relative flex items-start group">
                    <div class="absolute left-0 sm:left-4 w-16 h-16 rounded-full bg-neutral-primary shadow-sm border-2 border-brand flex items-center justify-center z-10 text-brand text-2xl font-['Playfair_Display'] font-bold group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                        4
                    </div>
                    
                    <div class="ml-20 sm:ml-32 w-full">
                        <div class="bg-neutral-primary-soft rounded-2xl p-6 sm:p-8 shadow-sm border border-border-default hover:shadow-md hover:border-brand/30 transition-all relative w-full duration-300">
                            <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-3">Jadwal Sumpah</h4>
                            <p class="text-[15px] text-body leading-relaxed">Bila berkas dinyatakan lengkap dan valid (Disetujui), maka admin akan menetapkan serta mengumumkan jadwal (Tanggal, Waktu, dan Lokasi) pelaksanaan sidang luar biasa pengambilan sumpah.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Step 5 -->
                <div class="relative flex items-start group">
                    <div class="absolute left-0 sm:left-4 w-16 h-16 rounded-full bg-neutral-primary shadow-sm border-2 border-brand flex items-center justify-center z-10 text-brand text-2xl font-['Playfair_Display'] font-bold group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                        5
                    </div>
                    
                    <div class="ml-20 sm:ml-32 w-full">
                        <div class="bg-neutral-primary-soft rounded-2xl p-6 sm:p-8 shadow-sm border border-border-default hover:shadow-md hover:border-brand/30 transition-all relative w-full duration-300">
                            <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-3">Pelaksanaan</h4>
                            <p class="text-[15px] text-body leading-relaxed">Calon advokat hadir pada jadwal yang telah ditentukan dengan membawa seluruh dokumen asli fisik untuk dicocokkan kembali kelengkapannya sebelum proses penyumpahan oleh Ketua Pengadilan Tinggi.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Step 6 -->
                <div class="relative flex items-start group pb-4">
                    <div class="absolute left-0 sm:left-4 w-16 h-16 rounded-full bg-neutral-primary shadow-sm border-2 border-brand flex items-center justify-center z-10 text-brand text-2xl font-['Playfair_Display'] font-bold group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                        6
                    </div>
                    
                    <div class="ml-20 sm:ml-32 w-full">
                        <div class="bg-neutral-primary-soft rounded-2xl p-6 sm:p-8 shadow-sm border border-border-default hover:shadow-md hover:border-brand/30 transition-all relative w-full duration-300">
                            <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-3">Pengambilan BAS</h4>
                            <p class="text-[15px] text-body leading-relaxed">Setelah sidang luar biasa pengambilan sumpah selesai dilaksanakan, Advokat baru dapat segera mengambil Berita Acara Sumpah (BAS). Status akhir permohonan pada sistem akan berubah menjadi Selesai.</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

    </div>
</section>
@endsection
