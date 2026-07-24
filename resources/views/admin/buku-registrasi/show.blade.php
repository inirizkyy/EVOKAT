@extends('layouts.admin')
@section('title', 'Detail Buku Registrasi Advokat')

@section('actions')
<a href="{{ route('admin.buku-registrasi.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Informasi Registrasi Permohonan Card -->
    <div class="bg-white rounded-xl shadow-sm border border-border-default p-6">
        <h5 class="font-bold text-brand mb-4 pb-2 border-b border-border-default flex items-center gap-2">
            <i class="fa-solid fa-id-card"></i> Informasi Identitas &amp; SK
        </h5>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-4 text-sm">
            <div>
                <span class="block text-body-subtle font-medium">Nomor Registrasi</span>
                <span class="text-heading font-bold font-mono text-[15px]">{{ $permohonan->nomor_permohonan }}</span>
            </div>
            <div>
                <span class="block text-body-subtle font-medium">Organisasi Advokat</span>
                <span class="text-heading font-semibold text-brand-strong">{{ $permohonan->organisasi->nama_organisasi ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-body-subtle font-medium">Nomor Surat Pengantar Organisasi </span>
                <span class="text-heading font-semibold">{{ $permohonan->nomor_sk ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-body-subtle font-medium">Tanggal Surat Pengantar</span>
                <span class="text-heading font-semibold">{{ $permohonan->tanggal_sk ? \Carbon\Carbon::parse($permohonan->tanggal_sk)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div>
                <span class="block text-body-subtle font-medium">Nomor HP / WA</span>
                <span class="text-heading font-semibold">{{ $permohonan->no_hp_organisasi ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-body-subtle font-medium">Email Organisasi</span>
                <span class="text-heading font-semibold">{{ $permohonan->email_organisasi ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-body-subtle font-medium">Tanggal Pengajuan</span>
                <span class="text-heading font-semibold">{{ $permohonan->tanggal_pengajuan ? \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div>
                <span class="block text-body-subtle font-medium">Status Permohonan</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-success-soft text-fg-success-strong mt-0.5">
                    {{ $permohonan->status }}
                </span>
            </div>
        </div>
    </div>

    <!-- List Anggota Pemohon Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-border-default overflow-hidden">
        <div class="px-6 py-4 bg-neutral-secondary-soft border-b border-border-default flex justify-between items-center">
            <h5 class="font-bold text-heading flex items-center gap-2 m-0">
                <i class="fa-solid fa-users text-brand"></i> Daftar Anggota Pemohon
            </h5>
            <span class="text-xs font-bold text-body-subtle">{{ $permohonan->pemohons()->whereHas('bukuRegistrasi')->count() }} Anggota Terdaftar</span>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                <thead class="bg-neutral-secondary-soft/50 border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-3 font-medium w-[3%]">No</th>
                        <th class="px-6 py-3 font-medium w-[8%]">Foto</th>
                        <th class="px-6 py-3 font-medium">Nama &amp; NIK</th>
                        <th class="px-6 py-3 font-medium">Alamat</th>
                        <th class="px-6 py-3 font-medium">Organisasi</th>
                        <th class="px-6 py-3 font-medium">SK Advokat</th>
                        <th class="px-6 py-3 font-medium">BAS &amp; Sumpah</th>
                        <th class="px-6 py-3 font-medium text-center w-[12%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-default bg-white">
                    @forelse($permohonan->pemohons()->whereHas('bukuRegistrasi')->get() as $index => $pemohon)
                        @php
                            $bukuReg = $pemohon->bukuRegistrasi;
                        @endphp
                        <tr class="hover:bg-neutral-secondary-soft/35 transition-colors">
                            <!-- Nomor -->
                            <td class="px-6 py-4">{{ $index + 1 }}</td>

                            <!-- Foto -->
                            <td class="px-6 py-4">
                                @if($pemohon->foto)
                                    <a href="{{ asset('storage/' . $pemohon->foto) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $pemohon->foto) }}" alt="Foto Pemohon" class="w-12 h-12 object-cover rounded shadow-sm border border-border-default hover:scale-105 transition-transform" style="width: 48px !important; height: 48px !important; min-width: 48px !important; min-height: 48px !important; max-width: 48px !important; max-height: 48px !important; object-fit: cover !important; aspect-ratio: 1/1 !important;">
                                    </a>
                                @else
                                    <div class="w-12 h-12 bg-neutral-secondary-soft rounded border border-border-default flex items-center justify-center text-body-subtle">
                                        <i class="fa-solid fa-user text-lg"></i>
                                    </div>
                                @endif
                            </td>

                            <!-- Nama & NIK -->
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="font-bold text-heading text-[15px]">{{ $pemohon->nama_lengkap }}</div>
                                <div class="text-[13px] text-body-subtle mt-1 font-mono">NIK: {{ $pemohon->nik }}</div>
                            </td>

                            <!-- Alamat -->
                            <td class="px-6 py-4 whitespace-normal max-w-[200px]">
                                <span class="line-clamp-2" title="{{ $pemohon->alamat ?? '-' }}">{{ $pemohon->alamat ?? '-' }}</span>
                            </td>

                            <!-- Organisasi -->
                            <td class="px-6 py-4">
                                <span class="font-semibold text-heading">{{ $permohonan->organisasi->nama_organisasi ?? '-' }}</span>
                            </td>

                            <!-- SK Advokat -->
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="font-medium text-heading">No: {{ $permohonan->nomor_sk ?? $pemohon->nomor_sk ?? '-' }}</div>
                                <div class="text-[12px] text-body-subtle mt-1">Tgl: {{ ($permohonan->tanggal_sk ?? $pemohon->tanggal_sk) ? \Carbon\Carbon::parse($permohonan->tanggal_sk ?? $pemohon->tanggal_sk)->translatedFormat('d M Y') : '-' }}</div>
                            </td>

                            <!-- BAS & Sumpah -->
                            <td class="px-6 py-4 whitespace-normal">
                                @if($bukuReg && $bukuReg->nomor_bas)
                                    <div class="text-heading font-medium text-[14px]">BAS: <span class="font-mono font-semibold text-brand">{{ $bukuReg->nomor_bas }}</span></div>
                                    <div class="text-[14px] text-heading font-medium mt-0.5"><i class="fa-solid fa-calendar mr-1 text-brand"></i>Sumpah: {{ \Carbon\Carbon::parse($bukuReg->tanggal_disumpah)->translatedFormat('d M Y') }}</div>
                                    <div class="text-[14px] text-body-subtle mt-0.5">Oleh: {{ $bukuReg->ketua_pengadilan_tinggi }}</div>
                                @else
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex items-center w-fit px-2 py-0.5 rounded text-xs font-semibold bg-warning-soft border border-border-warning-subtle text-fg-warning">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>Belum Lengkap
                                        </span>
                                        @if($bukuReg)
                                            @if($permohonan->status === 'Selesai')
                                                <a href="{{ route('admin.buku-registrasi.edit', $bukuReg->id) }}" class="text-[12px] text-brand hover:underline font-semibold flex items-center gap-1">
                                                    <i class="fa-solid fa-pen-to-square"></i> Lengkapi Data Sumpah
                                                </a>
                                            @else
                                                <span title="Permohonan belum berstatus Selesai (Tidak dapat diisi)" class="text-[12px] text-gray-400 font-semibold flex items-center gap-1 cursor-not-allowed">
                                                    <i class="fa-solid fa-lock"></i> Menunggu Status Selesai
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 text-center">
                                @if($bukuReg)
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Detail Member -->
                                        <a href="{{ route('admin.buku-registrasi.show-member', $bukuReg->id) }}" title="Lihat Detail Anggota" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <!-- Edit Sumpah -->
                                        @if($bukuReg->status_pemeriksa === 'Disetujui')
                                            <span title="Data Terkunci (Sudah Disetujui Pemeriksa)" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed opacity-60">
                                                <i class="fa-solid fa-lock text-xs"></i>
                                            </span>
                                        @elseif($permohonan->status === 'Selesai')
                                            <a href="{{ route('admin.buku-registrasi.edit', $bukuReg->id) }}" title="Lengkapi Data Sumpah" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-warning shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                                <i class="fa-solid fa-pencil text-xs"></i>
                                            </a>
                                        @else
                                            <button type="button" disabled title="Permohonan belum berstatus Selesai (Tidak dapat diisi)" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed opacity-60">
                                                <i class="fa-solid fa-lock text-xs"></i>
                                            </button>
                                        @endif
                                        <!-- Print Card -->
                                        <a href="{{ route('admin.buku-registrasi.print', $bukuReg->id) }}" target="_blank" title="Cetak Data" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-success shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                            <i class="fa-solid fa-print text-xs"></i>
                                        </a>
                                        <!-- Hapus Data -->
                                        @if($bukuReg->status_pemeriksa === 'Disetujui')
                                            <span title="Data Terkunci (Sudah Disetujui Pemeriksa)" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed opacity-60">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </span>
                                        @else
                                            <form action="{{ route('admin.buku-registrasi.destroy', $bukuReg->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data buku registrasi {{ addslashes($pemohon->nama_lengkap ?? '') }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Data" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-danger hover:bg-danger hover:text-white shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-body-subtle italic text-xs">Belum Masuk Buku Registrasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-body-subtle italic">Tidak ada anggota yang terdaftar dalam buku registrasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
