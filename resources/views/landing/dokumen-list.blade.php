@extends('layouts.frontend')
@section('title', 'Kelengkapan Dokumen Persyaratan')

@section('content')
<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Pengisian Berkas</h1>
            <p class="text-body-subtle text-lg">Unggah seluruh dokumen persyaratan untuk setiap anggota yang didaftarkan.</p>
        </div>
        
        <div id="form-container" class="bg-neutral-primary-soft rounded-[2rem] shadow-md border border-border-default p-6 sm:p-10 lg:p-12 relative overflow-hidden">
            
            <!-- Background Decorative Elements -->
            <div class="absolute right-0 top-0 text-brand opacity-5 text-[15rem] pointer-events-none select-none -z-10 translate-x-1/4 -translate-y-1/4">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>

            @if(session('nomor_permohonan'))
            <!-- Registration Banner Notification -->
            <div class="mb-8 rounded-2xl border border-border-success-subtle bg-success-soft relative z-10 overflow-hidden shadow-sm">
                <div class="px-6 pt-6 pb-4 flex items-center gap-3 border-b border-border-success-subtle">
                    <div class="w-9 h-9 rounded-full bg-white border border-border-success-subtle flex items-center justify-center text-fg-success-strong flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-fg-success-strong text-[16px]">Permohonan Berhasil Didaftarkan!</p>
                        <p class="text-[13px] text-fg-success">
                            Berikut adalah Nomor Registrasi resmi permohonan Anda. Nomor ini telah dikirimkan ke email organisasi.
                        </p>
                    </div>
                </div>
                <div class="px-6 py-5 flex flex-col items-center gap-3">
                    <p class="text-[13px] font-medium text-fg-success uppercase tracking-widest">Nomor Registrasi Anda</p>
                    <div class="flex items-center gap-3">
                        <span id="nomorRegistrasi" class="font-mono text-2xl sm:text-3xl font-bold text-fg-success-strong tracking-widest">{{ session('nomor_permohonan') }}</span>
                        <button type="button" onclick="copyNomor()" title="Salin nomor" class="w-9 h-9 rounded-full border border-border-success-subtle bg-white text-fg-success hover:text-fg-success-strong hover:shadow-md transition-all flex items-center justify-center flex-shrink-0">
                            <i id="copyIcon" class="fa-regular fa-copy text-base"></i>
                        </button>
                    </div>
                    <p class="text-[14px] sm:text-[15px] font-bold text-fg-success-strong text-center">
                        <i class="fa-solid fa-paper-plane mr-1"></i> Nomor Registrasi ini telah dikirimkan ke email: <strong>{{ session('email_terkirim') ?? $permohonan->email_organisasi }}</strong>
                    </p>
                    <p class="text-[12px] sm:text-[13px] text-fg-danger-strong text-center font-semibold mt-1">
                        <i class="fa-solid fa-circle-exclamation mr-1 text-fg-danger-strong"></i> Apabila email tidak masuk di Inbox utama, silakan periksa folder <strong>Spam</strong> atau <strong>Promosi</strong>.
                    </p>
                </div>
            </div>
            @elseif(session('success'))
            <div class="mb-8 p-4 rounded-xl bg-success-soft border border-border-success-subtle text-fg-success-strong flex items-start gap-3">
                <i class="fa-solid fa-circle-check mt-1"></i>
                <p class="text-[14px]">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-8 p-4 rounded-xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong flex items-start gap-3">
                <i class="fa-solid fa-circle-xmark mt-1"></i>
                <p class="text-[14px]">{{ session('error') }}</p>
            </div>
            @endif

            <!-- Step Indicator (Step 2 active) -->
            <div class="flex items-start justify-center mb-12">
                <div class="flex items-start space-x-2 sm:space-x-4">
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold transition-all duration-300 shadow-sm bg-brand text-white">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-brand">Data Organisasi &amp; Anggota</span>
                    </div>
                    
                    <div class="h-[4px] w-8 sm:w-16 bg-brand rounded-full mt-[24px] sm:mt-[26px]"></div>
                    
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold transition-all duration-300 shadow-sm bg-brand text-white ring-4 sm:ring-8 ring-brand-softer">
                            2
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-brand">Upload Dokumen</span>
                    </div>

                    <div class="h-[4px] w-8 sm:w-16 bg-border-default rounded-full mt-[24px] sm:mt-[26px]">
                        <div class="h-full bg-brand transition-all duration-500 rounded-full" style="width: 0%"></div>
                    </div>
                    
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold transition-all duration-300 shadow-sm bg-white text-body-subtle border border-border-default">
                            3
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-body-subtle">Submit</span>
                    </div>
                </div>
            </div>

            <div class="mb-8 p-5 rounded-2xl bg-white border border-border-default shadow-sm">
                <h4 class="font-['Playfair_Display'] text-lg font-bold text-heading mb-3"><i class="fa-solid fa-building text-brand mr-2"></i> Informasi Organisasi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[14.5px] font-medium text-heading">
                    <div><span class="text-body-subtle">Nama Organisasi:</span> {{ $permohonan->organisasi->nama_organisasi ?? '-' }}</div>
                    <div><span class="text-body-subtle">Nomor SK:</span> {{ $permohonan->nomor_sk }}</div>
                    <div><span class="text-body-subtle">Tanggal SK:</span> {{ \Carbon\Carbon::parse($permohonan->tanggal_sk)->translatedFormat('d F Y') }}</div>
                    <div><span class="text-body-subtle">WhatsApp Organisasi:</span> {{ $permohonan->no_hp_organisasi }}</div>
                </div>
            </div>

            <div class="text-center mb-8 border-b border-border-default pb-4">
                <h3 class="font-['Playfair_Display'] text-2xl font-bold text-heading">Daftar Dokumen Anggota</h3>
                <p class="text-[15px] text-body-subtle mt-2">Seluruh berkas wajib masing-masing anggota harus berstatus lengkap sebelum mengirimkan permohonan.</p>
            </div>

            <!-- MEMBERS LIST -->
            <div class="space-y-4 mb-10">
                @php $allComplete = true; @endphp
                @foreach($permohonan->pemohons as $index => $pemohon)
                    <div class="bg-white rounded-2xl border border-border-default p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center font-bold text-heading shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h5 class="font-bold text-heading text-[16px]">{{ $pemohon->nama_lengkap }}</h5>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-x-4 gap-y-1 mt-1 text-[13px] text-body-subtle">
                                    <span><i class="fa-regular fa-id-card mr-1.5"></i>{{ $pemohon->nik }}</span>
                                    <span><i class="fa-regular fa-envelope mr-1.5"></i>{{ $pemohon->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end shrink-0">
                            <!-- Kelengkapan status -->
                            @if($pemohon->is_complete)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">
                                    <i class="fa-solid fa-circle-check mr-1.5"></i> Sudah Lengkap
                                </span>
                            @else
                                @php $allComplete = false; @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">
                                    <i class="fa-solid fa-circle-xmark mr-1.5"></i> Belum Lengkap
                                </span>
                            @endif

                            <a href="{{ route('permohonan.dokumen-upload', [$permohonan->nomor_permohonan, $pemohon->id]) }}" class="inline-flex justify-center items-center px-4 py-2 rounded-full text-[13px] font-bold bg-neutral-primary-soft text-brand hover:bg-neutral-secondary-soft transition-all border border-brand shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload Dokumen
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- SUBMIT FORM -->
            <form id="submitForm" action="{{ route('permohonan.submit', $permohonan->nomor_permohonan) }}" method="POST" class="pt-6 border-t border-border-default flex flex-col sm:flex-row justify-between items-center gap-4">
                @csrf
                <div class="text-center sm:text-left">
                    <p class="text-[14px] text-body font-medium">Nomor Registrasi Sementara: <strong class="font-mono text-brand">{{ $permohonan->nomor_permohonan }}</strong></p>
                    <p class="text-[13px] text-body-subtle mt-0.5">Simpan nomor ini untuk melanjutkan pengisian berkas di lain waktu.</p>
                </div>
                
                <button type="submit" @if(!$allComplete) disabled @endif class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 rounded-full text-[15px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all border border-brand-softer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-md">
                    Kirim Permohonan <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function copyNomor() {
    const element = document.getElementById('nomorRegistrasi');
    if (!element) return;
    const nomor = element.textContent.trim();
    navigator.clipboard.writeText(nomor).then(() => {
        const icon = document.getElementById('copyIcon');
        if (icon) {
            icon.classList.remove('fa-regular', 'fa-copy');
            icon.classList.add('fa-solid', 'fa-check');
            setTimeout(() => {
                icon.classList.remove('fa-solid', 'fa-check');
                icon.classList.add('fa-regular', 'fa-copy');
            }, 2000);
        }
    });
}
</script>
@endpush
