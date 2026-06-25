@extends('layouts.frontend')
@section('title', 'Tracking Status Permohonan')

@section('content')
<section class="py-12 lg:py-20 bg-neutral-primary min-h-[calc(100vh-88px)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Tracking Permohonan</h1>
            <p class="text-body-subtle text-lg">Pantau status permohonan sumpah advokat Anda secara real-time.</p>
        </div>
        
        <div class="bg-neutral-primary-soft rounded-[2.5rem] shadow-xl border border-border-default p-8 sm:p-12 lg:p-16 max-w-3xl mx-auto mb-12 relative overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute -right-10 -top-10 text-brand opacity-5 text-[15rem] pointer-events-none select-none rotate-12 z-0">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
            </div>

            @if(session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-success-soft border border-border-success-subtle text-fg-success-strong flex items-start gap-3 relative z-10">
                <i class="fa-solid fa-circle-check mt-1"></i>
                <p class="text-[14px]">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-8 p-4 rounded-2xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong flex items-start gap-3 relative z-10">
                <i class="fa-solid fa-circle-xmark mt-1"></i>
                <p class="text-[14px]">{{ session('error') }}</p>
            </div>
            @endif

            <form action="{{ url('/tracking') }}" method="POST" class="relative z-10">
                @csrf
                <div class="mb-8">
                    <label class="block text-[18px] font-['Playfair_Display'] font-bold text-heading mb-4 text-center">Masukkan Nomor Registrasi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none transition-colors group-focus-within:text-brand text-body-subtle">
                            <i class="fa-solid fa-magnifying-glass text-xl"></i>
                        </div>
                        <input type="text" class="block w-full rounded-full border-2 border-border-default-medium bg-white shadow-lg text-[18px] sm:text-[20px] text-center text-heading py-5 px-16 focus:outline-none focus:border-brand focus:ring-4 focus:ring-brand/20 transition-all font-mono tracking-widest placeholder:tracking-normal placeholder:text-[16px]" name="nomor_permohonan" required placeholder="Contoh: ADV-20260701-0001">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="inline-flex justify-center items-center px-12 py-4 rounded-full text-[18px] font-bold bg-brand text-white shadow-md hover:shadow-xl hover:-translate-y-1 active:shadow-inset active:translate-y-0 transition-all duration-300 border border-brand-softer w-full sm:w-auto">
                        Lacak Status <i class="fa-solid fa-arrow-right-long ml-3"></i>
                    </button>
                </div>
            </form>
        </div>

        @if(isset($permohonan))
        <div class="bg-neutral-primary-soft rounded-[2rem] shadow-md border border-border-default p-6 sm:p-10 lg:p-12">
            <h3 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-8 pb-4 border-b border-border-default flex items-center gap-3">
                <span class="w-10 h-10 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-lg"><i class="fa-regular fa-file-lines"></i></span>
                Detail Permohonan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row border-b border-border-default border-dashed pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Nomor Registrasi</span>
                        <span class="text-heading font-bold sm:w-3/5 font-mono">{{ $permohonan->nomor_permohonan }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row border-b border-border-default border-dashed pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Tanggal Pengajuan</span>
                        <span class="text-heading sm:w-3/5">{{ \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->format('d F Y') }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5 mt-1">Status Saat Ini</span>
                        <span class="sm:w-3/5">
                            @if($permohonan->status == 'Menunggu Verifikasi')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-neutral-secondary-medium border border-border-default text-heading shadow-sm">{{ $permohonan->status }}</span>
                            @elseif($permohonan->status == 'Berkas Kurang' || $permohonan->status == 'Ditolak')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong shadow-sm">{{ $permohonan->status }}</span>
                            @elseif($permohonan->status == 'Disetujui' || $permohonan->status == 'Selesai')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong shadow-sm">{{ $permohonan->status }}</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info shadow-sm">{{ $permohonan->status }}</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row border-b border-border-default border-dashed pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Nama Pemohon</span>
                        <span class="text-heading sm:w-3/5 font-semibold">{{ $permohonan->pemohon->nama_lengkap }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row border-b border-border-default border-dashed pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">NIK</span>
                        <span class="text-heading sm:w-3/5">{{ $permohonan->pemohon->nik }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Organisasi</span>
                        <span class="text-heading sm:w-3/5">{{ $permohonan->pemohon->organisasi->nama_organisasi ?? '-' }}</span>
                    </div>
                </div>
            </div>

            @if($permohonan->catatan)
            <div class="mb-8 p-5 rounded-xl bg-warning-soft border border-border-warning-subtle text-fg-warning flex flex-col gap-2">
                <h5 class="font-bold flex items-center gap-2"><i class="fa-solid fa-bell"></i> Catatan Verifikasi:</h5>
                <p class="text-[14px] leading-relaxed ml-6">{{ $permohonan->catatan }}</p>
            </div>
            @endif

            @if($permohonan->jadwalSumpah)
            <div class="mb-10 p-6 rounded-2xl bg-success-soft border border-border-success-subtle text-fg-success relative overflow-hidden">
                <!-- Decorative Icon -->
                <i class="fa-regular fa-calendar-check absolute -right-4 -bottom-4 text-8xl text-success opacity-10 pointer-events-none"></i>
                
                <h5 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fa-regular fa-calendar-check"></i> Jadwal Sumpah Anda:</h5>
                <div class="h-px w-full bg-border-success-subtle mb-4"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Tanggal</span>
                        <span class="block text-lg font-bold text-fg-success-strong">{{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->tanggal)->format('d F Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Pukul</span>
                        <span class="block text-lg font-bold text-fg-success-strong">{{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->jam)->format('H:i') }} WIB</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Lokasi</span>
                        <span class="block text-[15px] font-bold text-fg-success-strong">{{ $permohonan->jadwalSumpah->lokasi }}</span>
                    </div>
                </div>
                
                @if($permohonan->jadwalSumpah->keterangan)
                <div class="mt-5 pt-4 border-t border-border-success-subtle relative z-10">
                    <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Keterangan:</span>
                    <p class="text-[14px]">{{ $permohonan->jadwalSumpah->keterangan }}</p>
                </div>
                @endif
            </div>
            @endif

            <h4 class="font-bold text-heading text-lg mt-10 mb-6 pb-2 border-b border-border-default">Riwayat Status</h4>
            
            <!-- Timeline Layout (Replacing Table) -->
            <div class="relative border-l border-brand ml-4 space-y-8 pb-4">
                @forelse($permohonan->riwayatStatus()->orderBy('changed_at', 'desc')->get() as $index => $riwayat)
                <div class="relative pl-8">
                    <!-- Timeline Dot -->
                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $index === 0 ? 'bg-brand ring-4 ring-brand-softer' : 'bg-neutral-primary border-2 border-brand' }}"></div>
                    
                    <div class="bg-neutral-primary rounded-xl p-4 shadow-sm border border-border-default hover:shadow-md transition-all">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2">
                            <span class="text-sm font-semibold text-heading">
                                Perubahan ke <span class="text-brand">{{ $riwayat->status_baru }}</span>
                            </span>
                            <span class="text-xs text-body-subtle bg-neutral-primary-soft shadow-inset px-2 py-1 rounded-md">
                                <i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($riwayat->changed_at)->format('d M Y - H:i') }}
                            </span>
                        </div>
                        <div class="text-xs text-body-subtle mb-2">
                            Dari status: <span class="px-1.5 py-0.5 bg-neutral-secondary-medium rounded text-heading">{{ $riwayat->status_lama }}</span>
                        </div>
                        @if($riwayat->keterangan)
                        <p class="text-[13px] text-body bg-neutral-primary-soft p-2.5 rounded-lg border border-border-default shadow-inset mt-3">
                            {{ $riwayat->keterangan }}
                        </p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="pl-8 text-body-subtle italic text-sm">
                    Belum ada riwayat perubahan status.
                </div>
                @endforelse
            </div>

        </div>
        @endif

    </div>
</section>
@endsection
