@extends('layouts.admin')
@section('title', 'Detail Buku Registrasi Advokat')

@section('actions')
<a href="{{ route($role . '.buku-registrasi.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Foto Pemohon -->
    <div class="bg-white rounded-xl shadow-sm border border-border-default p-6 flex flex-col items-center justify-center text-center h-fit">
        <h5 class="font-bold text-heading mb-4">Foto Pemohon</h5>
        @if($reg->pemohon && $reg->pemohon->foto)
            <a href="{{ asset('storage/' . $reg->pemohon->foto) }}" target="_blank">
                <img src="{{ asset('storage/' . $reg->pemohon->foto) }}" alt="Foto Pemohon" class="w-48 h-48 object-cover rounded-xl shadow-md border-2 border-border-default mb-4" style="width: 192px !important; height: 192px !important; object-fit: cover !important;">
            </a>
        @else
            <div class="w-48 h-48 bg-neutral-secondary-soft rounded-xl border border-border-default flex items-center justify-center text-body-subtle mb-4">
                <i class="fa-solid fa-user text-5xl"></i>
            </div>
        @endif
        <h6 class="font-bold text-heading text-[16px]">{{ $reg->pemohon->nama_lengkap }}</h6>
        <p class="text-sm text-body-subtle mt-1">NIK: {{ $reg->pemohon->nik }}</p>
    </div>

    <!-- Data Detail -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Identitas & SK -->
        <div class="bg-white rounded-xl shadow-sm border border-border-default p-6">
            <h5 class="font-bold text-brand mb-4 pb-2 border-b border-border-default flex items-center gap-2">
                <i class="fa-solid fa-id-card"></i> Informasi Identitas &amp; SK
            </h5>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <span class="block text-body-subtle font-medium">Tempat, Tanggal Lahir</span>
                    <span class="text-heading font-semibold">{{ $reg->pemohon->tempat_lahir }}, {{ \Carbon\Carbon::parse($reg->pemohon->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Jenis Kelamin</span>
                    <span class="text-heading font-semibold">{{ $reg->pemohon->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                </div>
                <div class="sm:col-span-2">
                    <span class="block text-body-subtle font-medium">Alamat Lengkap</span>
                    <span class="text-heading font-semibold">{{ $reg->pemohon->alamat ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Email</span>
                    <span class="text-heading font-semibold">{{ $reg->pemohon->email }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Nomor HP/WA</span>
                    <span class="text-heading font-semibold">{{ $reg->pemohon->no_hp ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Organisasi Advokat</span>
                    <span class="text-heading font-semibold text-brand-strong">{{ $reg->pemohon->organisasi->nama_organisasi ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Nomor Registrasi Permohonan</span>
                    <span class="text-heading font-bold font-mono">{{ $reg->permohonan->nomor_permohonan }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Nomor SK Advokat</span>
                    <span class="text-heading font-semibold">{{ $reg->permohonan->nomor_sk ?? $reg->pemohon->nomor_sk ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Tanggal SK Advokat</span>
                    <span class="text-heading font-semibold">{{ ($reg->permohonan->tanggal_sk ?? $reg->pemohon->tanggal_sk) ? \Carbon\Carbon::parse($reg->permohonan->tanggal_sk ?? $reg->pemohon->tanggal_sk)->translatedFormat('d F Y') : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Berita Acara Sumpah (BAS) -->
        <div class="bg-white rounded-xl shadow-sm border border-border-default p-6">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-border-default">
                <h5 class="font-bold text-brand m-0 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i> Berita Acara Sumpah (BAS)
                </h5>
                <span class="text-xs font-bold text-gray-400">
                    <i class="fa-solid fa-lock"></i> View Only
                </span>
            </div>

            @if($reg->nomor_bas)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <span class="block text-body-subtle font-medium">Nomor BAS</span>
                    <span class="text-heading font-bold font-mono text-brand-strong">{{ $reg->nomor_bas }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Tanggal Disumpah</span>
                    <span class="text-heading font-semibold">{{ \Carbon\Carbon::parse($reg->tanggal_disumpah)->translatedFormat('d F Y') }}</span>
                </div>
                <div>
                    <span class="block text-body-subtle font-medium">Ketua Pengadilan Tinggi yang Menyumpah</span>
                    <span class="text-heading font-semibold">{{ $reg->ketua_pengadilan_tinggi }}</span>
                </div>
                <div class="sm:col-span-2">
                    <span class="block text-body-subtle font-medium">Saksi-saksi</span>
                    <span class="text-heading font-semibold whitespace-pre-line">{{ $reg->saksi }}</span>
                </div>
            </div>
            @else
            <div class="text-center py-6 text-body-subtle">
                <i class="fa-solid fa-circle-info text-2xl text-warning mb-2"></i>
                <p class="text-sm">Data Berita Acara Sumpah (BAS) belum lengkap.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
